function setTime() {

    if (!jQuery('#timeToGo .seconds').length) return;

    var seconds = parseInt(jQuery('#timeToGo .seconds').html());
    var secondsT;
    seconds--;
    if (seconds<0) {
        secondsT="59";
        var minutes = parseInt(jQuery('#timeToGo .minutes').html());
        var minutesT;
        minutes--;
        if (minutes<0) {
            minutesT="59";
            var hours = parseInt(jQuery('#timeToGo .hours').html());
            var hoursT;
            hours--;
            if (hours<0) {
                hoursT="23";
                var days = parseInt(jQuery('#timeToGo .days').html());
                var daysT;
                days--;
                if (days<0) {
                    jQuery('#timeToGo, #timeToGoMobile').html('¡Ha empezado la Enroporra!');
                    return;
                }
                else daysT=days;
                jQuery('#timeToGo .days, #timeToGoMobile .days').html(daysT);
            }
            else if (hours<10) hoursT="0"+hours;
            else hoursT=hours;
            jQuery('#timeToGo .hours, #timeToGoMobile .hours').html(hoursT);
        }
        else if (minutes<10) minutesT="0"+minutes;
        else minutesT=minutes;
        jQuery('#timeToGo .minutes, #timeToGoMobile .minutes').html(minutesT);
    }
    else if (seconds<10) secondsT="0"+seconds;
    else secondsT=seconds;

    jQuery('#timeToGo .seconds, #timeToGoMobile .seconds').html(secondsT);
}
setInterval(setTime, 1000);
