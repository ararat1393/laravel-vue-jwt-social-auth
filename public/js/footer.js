$( document ).ready( function(){

	$.fn.hasAttr = function(name) {  
	   return this.attr(name) !== undefined;
	};
	// open delete popup
	$(document).on('click','.delete-it',function(){
		let url = $(this).attr('url');

		const swalWithBootstrapButtons = Swal.mixin({
		  customClass: {
		    confirmButton: 'btn btn-success m-2',
		    cancelButton: 'btn btn-danger m-2'
		  },
		  buttonsStyling: false,
		})

		swalWithBootstrapButtons.fire({
		  title: 'Are you sure?',
		  text: "You won't be able to revert this!",
		  type: 'warning',
		  showCancelButton: true,
		  confirmButtonText: 'Yes, delete it!',
		  cancelButtonText: 'No, cancel!',
		  reverseButtons: true
		}).then((result) => {
		  if (result.value) {
		  	
		  	window.location.href = url;
		  	
		  } else if (
		    // Read more about handling dismissals
		    result.dismiss === Swal.DismissReason.cancel
		  ) {
		    swalWithBootstrapButtons.fire(
		      'Cancelled',
		      'Your imaginary data is safe :)',
		      'error'
		    )
		  }
		})
	});

	// open popup for qr code
	(function createPopUp() {
	
	let div = $(`<div id='popup-qr'></div>`);
		div.append(`
			<button type="button" class="btn btn-primary open-popup-qr" data-toggle="modal" data-target="#QRModal" style="display:none;"></button>
				  <!-- The Modal -->
			  <div class="modal fade" id="QRModal" style="background: transparent;box-shadow: none;">
			    <div class="modal-dialog modal-sm">
			      <div class="modal-content p-0" >
			      
			        <!-- Modal Header -->
			        <div class="modal-header " style='justify-content: center;'>
			          <h4 class="modal-title text-center" style='color:#f11e0c'>Scan QR</h4>
			          <button type="button" class="close m-0 position-absolute" style='top:0;right: 0' data-dismiss="modal">&times;</button>
			        </div>
			        
			        <!-- Modal body -->
			        <div class="modal-body text-center">
						{{-- QR Code --}}
			        </div>

			      </div>
			    </div>
			  </div>`);
	$('body .section-main').append( div );
	console.log('Popup for Qr code successfully created');
	})();
	

// -------------------------------------------------------------------------------------------------------
	(function showActiveMenu(){
		let href = window.location.href;
		let path = window.location.pathname;

		if( path == '/admin' || path == '/admin/'){
			$('.logo-wrapper .logo-text').addClass('text-info');
			return;
		}

		$('.sidenav a').each(function(){
			if( $(this).attr('href') == href ){
				$(this).addClass('active');
				if( $(this).hasAttr('sub') ){
					$(this).parents('li').addClass('active open');
					$(this).find('span').addClass('sub-span');
					$(this).find('i').addClass('sub-i');
				}
				return;
			}
		})
	})();
// -----------------------------------------------------------------------------------------------------------
	var notificationsCount = 0;
	(function findNotification(){
		$.post('/admin/find-new-notification',function(response){
			let notifications_count = 0;
			let li;
			for( var name in response ){
				notifications_count += response[name].length;
			}
			$('#notifications-dropdown').find('span[data-count]').attr('data-count',notifications_count);
			$('#notifications-dropdown').find('span[data-count]').html(notifications_count);
			if( notifications_count ){
				$('.notification-button i').append(`<small class="notification-badge bg-danger" style="font-size: 40%">${notifications_count}</small>`);
			}
			notificationsCount = notifications_count;
			if( response.contacts && response.contacts.length !=0 ){
				response.contacts.forEach((contact,index)=>{
					var passed = differenceDate(contact.created_at);
					$('#notifications-dropdown').append(`
						<li>
							<a class="grey-text text-darken-2" href="/admin/contacts">
								<span class="material-icons icon-bg-circle amber small">trending_up</span>Added New Contact Us Message
							</a>
			              <time class="media-meta" datetime="${passed}">${passed}</time>
			            </li>
					`);
				});
			}
		})
	})();

	Pusher.logToConsole = true;
	var pusher = new Pusher('9d05fdb3dc7529face03', {
		cluster: 'mt1',
		forceTLS: true
	});

	var channel = pusher.subscribe('conntact-us');
	var notificationsWrapper   = $('#notifications-dropdown');

	if (notificationsCount <= 0) {
		notificationsWrapper.hide();
	}

	channel.bind('new-notify', function(data) {
		var passed = differenceDate(data.contact.created_at);
		$('#notifications-dropdown').find('.divider').after(`
			<li>
				<a class="grey-text text-darken-2" href="/admin/contacts">
					<span class="material-icons icon-bg-circle amber small">trending_up</span>Added New Contact Us Message
				</a>
				<time class="media-meta" datetime="${passed}">${passed}</time>
			</li>	
		`);
		notificationsCount += 1;
		$('#notifications-dropdown').find('span[data-count]').attr('data-count',notificationsCount);
		$('#notifications-dropdown').find('span[data-count]').html(notificationsCount);
		$('.notification-button i').find('small').html(notificationsCount);
        notificationsWrapper.show();
	});

	$(document).on('click','.show-qr',function(){
		$('#QRModal .modal-body').empty();
		
		let qr = $(this).attr('qr');
		$('#QRModal .modal-body').append( qr );
		$('.open-popup-qr').click();
	});

	function differenceDate( time ){

         let today = new Date()
         let date  = new Date(time);
         let passed = '';

         var res = Math.abs(today - date) / 1000;
         // get total days between two dates
         var days = Math.floor(res / 86400);                      
         // get hours        
         var hours = Math.floor(res / 3600) % 24;         
         // get minutes
         var minutes = Math.floor(res / 60) % 60;      
         // get seconds
         var seconds = res % 60;

         if( days ) passed += Math.round(days) + ' Days ';
         if( hours ) passed += Math.round(hours) + ' Hours ';
         if( minutes ) passed += Math.round(minutes) + ' Minute ';
         if( seconds ) passed += Math.round(seconds) + ' Second Ago';
         return passed;
	}

	$(document).on('change','#choose-merchant',function(){

		let expired = $(this).find(':selected').attr('expired');
		let limited = $(this).find(':selected').attr('limited');

		if( expired != '0' ){
			errorAlert( expired );
			return;
		}

		if( limited != '0'){
			errorAlert( limited );
			return;
		}
	})

    $('.tooltipped').tooltip();
           
})