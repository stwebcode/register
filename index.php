<?php
require_once 'config.php';
if(!isset($_SESSION['user']['id'])):
    header('Location: login.php'); 
else: ?>
<?php include_once "header.php"; ?>
<div id="evoCalendar"></div>
<?php if($_SESSION['user']['roleID'] == 2): ?>
    <button id="show-event-form">+ Jauns ieraksts</button>
    <div id="new-event" class="shadowbox">
        <div class="two-column-form">
            <br><span><button id="close-event-form">X</button></span>
            <label for="type">Tips</label>
            <div>
                <input id="type" list="types" type="text" name="type" autocomplete="off" required>
                <datalist id="types">
                    <option>Svētki</option>
                    <option>Atceres diena</option>
                    <option>Ekskursija</option>
                    <option>Eksāmens</option>
                </datalist>
                <p id="type-error"></p>
            </div>
            <label for="name">Nosaukums</label>
            <div>
                <input id="name" type="text" name="name" required>
                <p id="name-error"></p>
            </div>
            <label for="date">Datums</label>
            <div>
                <input id="date" type="date" name="date" required>
                <p id="date-error"></p>
            </div>
            <label for="time">Laiks</label>
            <div>
                <input id="time" type="time" name="time" required>
                <p id="time-error"></p>
            </div>
            <br>
            <label for="everyYear">Ikgadējs <input id="everyYear" type="checkbox" name="everyYear"></label>
            
            <label for="description">Apraksts</label>
            <textarea id="description" name="description" rows="8" cols="30"></textarea>
            <button id="submit-new-event">iesniegt</button>
        </div>
    </div>
<?php endif; ?>
<script src="calendar/evo-calendar.js"></script>
<script>
    $( document ).ready(function() {
        $("#new-event").hide();
        getEvents();
        $("#show-event-form").click(function(){$("#new-event").show()});
        $("#close-event-form").click(function(){$("#new-event").hide()});
        $("#submit-new-event").click(function(){
            if(validateEventForm()){
                submitNewEvent();
            }
        });
        $(document.body).on("click",".shadowbox", function(){
            $(".shadowbox").hide();
        });
        $(document.body).on("click",".shadowbox > *", function(event){
            event.stopPropagation();
        });
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
                    eventHeaderFormat: 'd. MM, yyyy',
                    firstDayOfWeek: 1, // Monday
                    todayHighlight: true,
                    calendarEvents: data
                });
                $("#show-event-form").appendTo('.calendar-months');
            }
            
        });
    }
    function validateEventForm(){
        $("#name-error").text("");
        $("#date-error").text("");
        $("#type-error").text("");
        $("#time-error").text("");
        const name = $("#name").val();
        const date = $("#date").val();
        const type = $("#type").val();
        const time = $("#time").val();
        // const description = $("#description").val();
        if(type == ""){
            $("#type-error").text("Šis lauks ir obligāts.");
            return false;
        }
        if(name.length == 0){
            $("#name-error").text("Šis lauks ir obligāts.");
            return false;
        }
        if(date == ""){
            $("#date-error").text("Šis lauks ir obligāts.");
            return false;
        }
        if(time == ""){
            $("#time-error").text("Šis lauks ir obligāts.");
            return false;
        }
        return true;
    }
    //nosūta form info serverim
    function submitNewEvent(){
        let eventDataToServer = {
            name: $("#name").val(),
            date: $("#date").val(),
            type: $("#type").val(),
            everyYear: $("#everyYear").is(":checked") ? 1 : 0,
            time: $("#time").val(),
            description: $("#description").val()
        }
        $.ajax({
            url: 'server.php',
            method: 'post',
            dataType: 'json',
            data: {
                action: 'insert_event',
                eventData: eventDataToServer
            },
            success: function(data){
                // console.log(data);
                insertCalendarEvent(data);
                $("#new-event").hide();
            },
            error: function(data){
                console.log(data);
            }
        });
    }
    // ievieto event no servera response
    function insertCalendarEvent(eventData){
        $("#evoCalendar").evoCalendar('addCalendarEvent', [eventData]);
    }
    // https://www.jqueryscript.net/time-clock/event-calendar-evo.html
</script>
<?php include_once "footer.php"; ?>
<?php endif; ?>