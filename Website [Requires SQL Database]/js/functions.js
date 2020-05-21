function punishUser(type, userID) {
    var msg = "";
    switch (type) {
        case "note":
            var noteMsg = prompt("What is the note you want to submit?:");
            noteMsg = noteMsg.replace(" ", "%20");
            console.log("Trying to change page");
            document.location.assign('/punishUser.php?punishType=note&UserID=' + userID + '&msg=' + noteMsg);
            break;
        case "warn":
            msg = prompt("What are you warning them for?:");
            msg = msg.replace(" ", "%20");
            document.location.assign('/punishUser.php?punishType=warn&UserID=' + userID + '&msg=' + msg);
            break;
        case "kick":
            msg = prompt("What are you kicking them for?:");
            msg = msg.replace(" ", "%20");
            document.location.assign('/punishUser.php?punishType=kick&UserID=' + userID + '&msg=' + msg);
            break;
        case "tempban":
            msg = prompt("What are you temp-banning them for?:");
            msg = msg.replace(" ", "%20");
            var typeTime = prompt("What is the time type? (HOUR, DAY, WEEK, MONTH)").toUpperCase();
            console.log("The time is: " + typeTime);
            if (typeTime === "HOUR" || typeTime === "DAY"
                || typeTime === "WEEK" || typeTime === "MONTH") {
                var time = prompt("How many " + typeTime.toLowerCase() + "s are we banning them for?:");
                console.log("The time is: " + time);
                if (!isNaN(time)) {
                    document.location.assign('/punishUser.php?punishType=tempban&UserID=' + userID + '&msg=' + msg
                        + '&tempbanType=' + typeTime.toLowerCase() + '&tempbanTime=' + time);
                } else {
                    // ERROR, not a valid number
                    alert("ERROR: That is not a valid number...");
                }
            } else {
                // ERROR, not a correct time type
                alert("ERROR: That is not a valid time type...");
            }
            break;
        case "ban":
            msg = prompt("What are you banning them for?:");
            msg = msg.replace(" ", "%20");
            document.location.assign('/punishUser.php?punishType=ban&UserID=' + userID + '&msg=' + msg);
            break;
    }
    //document.location.reload();
}

/** / AJAX Shit below /**/
function loadMoreUsers() {
    $('#load-more').hide();
    $('#load-gif').css("display", "block");
    $.ajax({url: '/AJAX/load-more-users.php', success: function (result) {
        // This is the result data that comes back from the ajax
            $('#load-gif').hide();
            $('#load-more').show();
            $("#players").append(result);
        }
    });
}
function loadMoreLogs() {
    $('#load-more-logs').hide();
    $('#load-gif').css("display", "block");
    $.ajax({url: '/AJAX/load-more-logs.php', success: function (result) {
            // This is the result data that comes back from the ajax
            $('#load-gif').hide();
            $('#load-more-logs').show();
            $("#logs").append(result);
        }
    });
}
function getAutoComplete() {
    var input = $('#players-searchbar').val();
    if (input.length >= 1) {
        $('#load-more').hide();
        $('#load-gif').css("display", "block");
        // We can do ajax cause they clicked search button
        $.ajax({url: '/AJAX/autocomplete.php?input=' + input, success: function (result) {
            // This is the result data that comes back
                $('#load-gif').hide();
                $('#load-more').show();
                $("#players tbody").empty();
                $("#players tbody").append(result);
            }
        });
    }
}
function getAutoCompleteKeyup() {
    var input = $("#players-searchbar").val();
    if (input.length >= 3) {
        $('#load-more').hide();
        $('#load-gif').css("display", "block");
        // We can do the ajax with 3 or more characters
        $.ajax({url: '/AJAX/autocomplete.php?input=' + input, success: function (result) {
                // This is the result data that comes back
                $('#load-gif').hide();
                $('#load-more').show();
                $("#players tbody").empty();
                $("#players tbody").append(result);
            }
        });
    }
}