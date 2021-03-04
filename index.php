<?php
require_once 'config.php';
if(!isset($_SESSION['user_id'])):
    header('Location: login.php'); 
else: ?>
<?php include_once "header.php"; ?>
<div id="evoCalendar"></div>
<div id="newEvent">
    <label for="name">Nosaukums</label>
    <input id="name" type="text" name="name">
    <label for="date">Datums</label>
    <input id="date" type="text" name="date">
    <label for="time">Laiks</label>
    <input id="time" type="text" name="time">
    <label for="everyYear">Ikgadējs</label>
    <input id="everyYear" type="checkbox" name="everyYear">
    <label for="color">Krāsa</label>
    <input id="color" type="text" name="color">
    <label for="type">tips</label>
    <input id="type" type="text" name="type">
    <label for="description">apraksts</label>
    <input id="description" type="text" name="description">
    <button id="submitNewEvent">iesniegt</button>
</div>
<script src="calendar/evo-calendar.js"></script>
<script>
    $( document ).ready(function() {
        getEvents();
        $("#submitNewEvent").click(function(){submitNewEvent()});
    });
    
    function getEvents(){
        $.ajax({
            url: 'server.php',
            method: 'post',
            dataType: 'json',
            data: {
                action: 'get_events'
            },
            success: function(data){
                
                $('#evoCalendar').evoCalendar({
                    theme: 'Midnight Blue',
                    language: 'lv',
                    format: 'mm/dd/yyyy',
                    titleFormat: 'MM yyyy',
                    eventHeaderFormat: 'MM d, yyyy',
                    firstDayOfWeek: 1, // Monday
                    todayHighlight: true,
                    calendarEvents: data
                });
            }
            
        });
    }
    //nosūta form info serverim
    function submitNewEvent(){
        let eventDataToServer = {
            name: $("#name").val(),
            date: $("#date").val(),
            type: $("#type").val(),
            everyYear: $("#everyYear").is(":checked") ? 1 : 0,
            time: $("#time").val(),
            description: $("#description").val(),
            color: $("#color").val()
        }
        console.log(eventDataToServer);
        $.ajax({
            url: 'server.php',
            method: 'post',
            dataType: 'json',
            data: {
                action: 'insert_event',
                eventData: eventDataToServer
            },
            success: function(data){
                // insertCalendarEvent(data);
            }
        });
    }
    // ievieto event no servera
    function insertCalendarEvent(eventData){
        $("#evoCalendar").evoCalendar('addCalendarEvent', [{eventData}]);
    }
    // https://www.jqueryscript.net/time-clock/event-calendar-evo.html
</script>
<?php include_once "footer.php"; ?>
<?php endif; ?>