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
function fill_header() {
    const xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (this.readyState === 4 && this.status === 200) {
            document.getElementById("header").innerHTML = this.responseText;
        }
    };
    xmlhttp.open("GET", "../model_ui/header.php", true);
    xmlhttp.send();
}
function fill_footer() {
    const xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (this.readyState === 4 && this.status === 200) {
            document.getElementById("footer").innerHTML = this.responseText;
        }
    };
    xmlhttp.open("GET", "../model_ui/footer.php", true);
    xmlhttp.send();
}
function fill_login_form($msg) {
    const xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (this.readyState === 4 && this.status === 200) {
            document.getElementById("login_form").innerHTML = this.responseText;
        }
    };
    xmlhttp.open("GET", "../model_ui/login_form.php?msg=" + $msg, true);
    xmlhttp.send();
}
function fill_account_status(status) {
    const xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (this.readyState === 4 && this.status === 200) {
            document.getElementById("account_status").innerHTML = this.responseText;
        }
    };
    xmlhttp.open("GET", "../model_ui/account_status.php?status=" + status, true);
    xmlhttp.send();
}
function get_scorecard($series_id, $match_id) {

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
            document.getElementById('this_over_summary').innerHTML =
                "Over " + scorecard.over + "  :  " + scorecard.this_over_summary;
        }
    };
    xmlhttp.open("GET", "https://om8zdfeo2h.execute-api.ap-south-1.amazonaws.com/scores/" + series_id + "/" + match_id + "/latest", true);
    xmlhttp.send();
}
function add_new_over_data() {
    let existing_overs_element = document.getElementsByName("over_id");
    if (existing_overs_element.length < 4) {
        let last_showing_over_id = Number(existing_overs_element.item(existing_overs_element.length - 1).getAttribute("id"));
        let series_id = getCookie("series_id");
        let match_id = getCookie("match_id");
        const xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function () {
            if (this.readyState === 4 && this.status === 200) {
                let scorecard = JSON.parse(this.responseText);
                document.getElementById("over-container")
                    .appendChild(create_new_over_element(scorecard.this_over, scorecard.over_id));
            }
        };
        xmlhttp.open("GET", "https://om8zdfeo2h.execute-api.ap-south-1.amazonaws.com/scores/" + series_id + "/" + match_id + "/" + (last_showing_over_id - 1), true);
        xmlhttp.send();
    } else {
        if(document.getElementsByName("no-more-overs").length === 0)
            document.getElementById("over-container").appendChild(create_no_more_overs());
    }
}
function create_new_over_element(this_over, over_id) {
    const div = document.createElement("div");
    div.setAttribute("class", "balls-container-extra");
    div.setAttribute("name", "over_id");
    div.setAttribute("id", over_id);

    const more_over_div = document.createElement("div");
    more_over_div.setAttribute("class", "more_over_div");
    more_over_div.innerHTML = "Over " + (over_id % 100);

    div.appendChild(more_over_div);

    let ball_id = 1;
    this_over.forEach((ball) => {
        const ball_div = document.createElement("div");
        let width = this_over.length < 4 ? 33.333 : 100 / this_over.length;
        ball_div.setAttribute("style", "width: "+width+"%; align-content: center");
        ball_div.setAttribute("class", "balls-extra");

        const ball_span = document.createElement("span");
        ball_span.setAttribute("id", "ball_id_"+ball_id);
        ball_span.innerHTML = ball;

        ball_div.appendChild(ball_span);
        div.appendChild(ball_div);
    });
    return div;
}
function create_no_more_overs() {
    const p = document.createElement("p");
    p.setAttribute("name", "no-more-overs");
    p.setAttribute("class", "no-more-over-para")
    p.innerHTML = "Only Last 4 overs allowed";
    return p;
}
function update_slot_details(amount) {
    const xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function () {
        if (this.readyState === 4 && this.status === 200) {
            let slot = JSON.parse(this.responseText);
            document.getElementById("slot_a_span").innerHTML = slot.slot1;
            document.getElementById("slot_b_span").innerHTML = slot.slot1;
        }
    };
    xmlhttp.open("GET", "../matches/GetSlotDetails.php?amount=" + amount, true);
    xmlhttp.send();
}