$('<img/>').attr('src', 'http://forum.sunday.com/bundles/sundayui/img/robot/igor_gsap.png').load(function(){
    var tlBlink = new TimelineMax({repeat: -1, repeatDelay: -1});
    tlBlink.to(".login-robot-body", 0.8, {backgroundPosition:"-240px 0", delay:3, ease:SteppedEase.config(3)});
    tlBlink.set(".login-robot-body", {backgroundPosition:"0 0"});
    tlBlink.to(".login-robot-body", 0.8, {backgroundPosition:"-240px 0", ease:SteppedEase.config(3)});
});
