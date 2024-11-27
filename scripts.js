function validate_register_form() {
    let fname = document.forms["register_form"]["fname"].value;
    let phone = document.forms["register_form"]["phone"].value;
    let password = document.forms["register_form"]["password"].value;
    let confirm_password = document.forms["register_form"]["fname"].value;

    if (fname.length < 2) {
        alert("First name must be at least 2 characters");
        return false;
    }
    if (phone.length !== 10) {
        alert("Phone Number must be of 10 digits.");
        return false;
    }
    if (password.length < 4) {
        alert("Password must be more than 2 characters");
        return false;
    }
    if (password === confirm_password) {
        alert("Password does not match");
        return false;
    }
    return true;
}

function getCookie(name) {
    const cookies = document.cookie.split('; ');
    for (const cookie of cookies) {
        const [key, value] = cookie.split('=');
        if (key === name) {
            return value;
        }
    }
    return null;
}
function fill_controls() {
    const xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (this.readyState === 4 && this.status === 200) {
            document.getElementById("main_controls").innerHTML = this.responseText;
        }
    };
    xmlhttp.open("GET", "../model_ui/main_controls.php", true);
    xmlhttp.send();
}
function fill_scorecard() {
    const xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (this.readyState === 4 && this.status === 200) {
            document.getElementById("scorecard").innerHTML = this.responseText;
            fill_scorecard_data();
        }
    };
    xmlhttp.open("GET", "../model_ui/scorecard.php", true);
    xmlhttp.send();
}
function fill_scorecard_data() {
    let series_id = getCookie("series_id");
    let match_id = getCookie("match_id");
    const xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (this.readyState === 4 && this.status === 200) {
            let scorecard = JSON.parse(this.responseText);
            document.getElementById("team1_name").innerHTML = scorecard.teams[0];
            let team1_score = scorecard.team1_score.runs + "/" + scorecard.team1_score.wickets + " (" + scorecard.team1_score.overs + ")";
            document.getElementById("team1_score").innerHTML = team1_score;
            document.getElementById("team2_name").innerHTML = scorecard.teams[1];
            let team2_score = scorecard.team2_score.runs + "/" + scorecard.team2_score.wickets + " (" + scorecard.team2_score.overs + ")";
            document.getElementById("team2_score").innerHTML = team2_score;
            document.getElementById("match_additional_details").innerHTML = scorecard.match_additional_details[0];
            document.getElementById('bowler').innerHTML = scorecard.bowler;
            document.getElementById('batsman1').innerHTML = scorecard.batsmen[0];
            document.getElementById('batsman2').innerHTML = scorecard.batsmen[1];
            for (let i = 1; i <= scorecard.this_over.length; i++){
                document.getElementById('ball_id_' + i).innerHTML = scorecard.this_over[i-1];
            }
            document.getElementById('this_over_summary').innerHTML = "Current Over : " + scorecard.this_over_summary;
        }
    };
    xmlhttp.open("GET", "https://om8zdfeo2h.execute-api.ap-south-1.amazonaws.com/scores/" + series_id + "/" + match_id + "/latest", true);
    xmlhttp.send();
}