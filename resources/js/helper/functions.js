
window.setCookie = function( name , value = ''){
    document.cookie = name +'='+ value +'; Path=/;';
}
window.deleteCookie = function( name ){
    document.cookie = name +'=; Path=/; Expires=Thu, 01 Jan 1970 00:00:01 GMT;';
}
window.getCookie = function( name ) {
    var match = document.cookie.match(new RegExp('(^| )' + name + '=([^;]+)'));
    return match ? match[2] : null ;
}
