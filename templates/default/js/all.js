$(function(){
    
});

function janimateCSS(element, animationName,callback,csswhendone =""){
    element.addClass("animated");
    element.addClass(animationName);
    function handleAnimationEnd(){
        if (csswhendone)
            element.addClass(csswhendone);
        element.removeClass("animated");
        element.removeClass(animationName);
        element.off("animationend",handleAnimationEnd);
        if (typeof callback === 'function') callback(element)

    }
    element.on("animationend",handleAnimationEnd);
}

function hideThisElement(element){
    setTimeout(function(){
        janimateCSS(element,"slideOutUp","","d-none");
    },3000);
}