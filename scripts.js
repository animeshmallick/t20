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
function cancel_bid_service(match_id, innings, over) {
    let x = 6;
    setInterval(function (){
        document.getElementById('cancel_bid').innerText = 'Cancel this bid ('+x+' seconds remaining)';
        x--;
        if (x < 0){
            document.getElementById('cancel_bid').style.display = 'none'
            document.getElementById('close').innerHTML = "Close"
        }
    }, 1000);
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
function get_slot_details(amount) {
    const match_id = getCookie('match_id');
    const innings = getCookie('innings');
    const overs = getCookie('overs');
    if (match_id == null || innings == null || overs == null)
        alert("Something went wrong");

    const xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (this.readyState === 4 && this.status === 200) {
            let msg = this.responseText.split("&&");
            document.getElementById("slot_a_span").innerHTML = msg[0];
            document.getElementById("slot_b_span").innerHTML = msg[1];
            document.getElementById("slot_c_span").innerHTML = msg[2];
        }
    };
    xmlhttp.open("GET", "GetBidDetailsPerOver.php?match_id="+match_id+"&innings="+innings+"&overs="+overs+"&amount="+amount, true);
    xmlhttp.send();
}
function get_scorecard_summary(team1_name, team1_score, team2_name, team2_score, match_additional_details,
                               bowler, batsman1, batsman2, this_over_string, this_over_summary){
    document.getElementById("team1_name").innerHTML = team1_name;
    document.getElementById("team1_score").innerHTML = team1_score;
    document.getElementById("team2_name").innerHTML = team2_name;
    document.getElementById("team2_score").innerHTML = team2_score;
    document.getElementById("match_additional_details").innerHTML = match_additional_details;
    document.getElementById('bowler').innerHTML = bowler;
    document.getElementById('batsman1').innerHTML = batsman1;
    document.getElementById('batsman2').innerHTML = batsman2;
    let this_over = this_over_string.split("&&");
    for (let i = 1; i <= this_over.length; i++){
        document.getElementById('ball_id_' + i).innerHTML = this_over[i-1];
    }
    document.getElementById('this_over_summary').innerHTML = "Current Over : " + this_over_summary;
}