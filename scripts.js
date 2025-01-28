let scorecard_timer, update_scorecard_timer, slots_timer, update_slots_timer;
let scorecard_time = 5, slots_time = 5;
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
function fill_scorecard(series_id, match_id) {
    fill_scorecard_ui(series_id, match_id);
    update_scorecard_timer = setInterval(function (){
        scorecard_time+= 5;
        document.getElementById('timer').innerHTML = "Updated "+scorecard_time+" sec ago";
    }, 5000);
    scorecard_timer = setInterval(function (){
        fill_scorecard_data(series_id, match_id);
    }, 14000);
}
function fill_scorecard_ui(series_id, match_id){
    const xmlhttp = new XMLHttpRequest();
    xmlhttp.onload = function() {
        if (this.readyState === 4 && this.status === 200) {
            document.getElementById("scorecard").innerHTML = this.responseText;
            fill_scorecard_data(series_id, match_id);
        }
    };
    xmlhttp.open("GET", "../model_ui/scorecard.php?series_id="+series_id+"&match_id="+match_id, true);
    xmlhttp.send();
}
function fill_scorecard_data(series_id, match_id){
    const xmlhttp = new XMLHttpRequest();
    xmlhttp.onload = function () {
        if (this.readyState === 4 && this.status === 200) {
            let scorecard = JSON.parse(this.responseText);
            fill_scorecard_content(scorecard);
        }
    };
    xmlhttp.open("GET", "https://om8zdfeo2h.execute-api.ap-south-1.amazonaws.com/scores/" + series_id + "/" + match_id + "/latest", true);
    xmlhttp.send();
}
function fill_scorecard_content(scorecard){
    let over_str_1 = scorecard.team1_score.overs;
    let over_str_2 = scorecard.team2_score.overs;
    if(scorecard.innings === 1){
        over_str_1 -= 1;
        let bls = get_valid_balls(scorecard.this_over);
        over_str_1 = over_str_1+"."+bls;
        if(bls === 6 || bls === 0)
            over_str_1 = scorecard.team1_score.overs;
    }
    if(scorecard.innings === 2){
        over_str_2 -= 1;
        let bls = get_valid_balls(scorecard.this_over);
        over_str_2 = over_str_2+"."+bls;
        if(bls === 6 || bls === 0)
            over_str_2 = scorecard.team2_score.overs;
    }
    let total_balls = 0;
    if(scorecard.innings === 1){
        let x = parseFloat(over_str_1) * 10;
        total_balls = (x * 6 / 10) + (x % 10);
    }else{
        let x = parseFloat(over_str_2) * 10;
        total_balls = (x * 6 / 10) + (x % 10);
    }
    if(total_balls <= 0){
        over_str_1 = 0;
        over_str_2 = 0;
    }
    let crr = 0;
    crr = (scorecard.innings === 1 ? scorecard.team1_score.runs : scorecard.team2_score.runs) / total_balls * 6;
    let rrr = scorecard.innings === 2 ? (scorecard.team1_score - scorecard.team2_score + 1) / (120 - total_balls) * 6 : 0;
    let team_score = [];
    document.getElementById('team1_logo').setAttribute('src', `../images/${scorecard.teams[0].toLowerCase()}.png`)
    document.getElementById('team2_logo').setAttribute('src', `../images/${scorecard.teams[1].toLowerCase()}.png`)
    document.getElementById("team1_name").innerHTML = scorecard.teams[0];
    team_score[0] = scorecard.team1_score.runs + "/" + scorecard.team1_score.wickets + " (" + over_str_1 + ")";
    document.getElementById("team1_score").innerHTML = team_score[0];
    document.getElementById("team2_name").innerHTML = scorecard.teams[1];
    team_score[1] = scorecard.team2_score.runs + "/" + scorecard.team2_score.wickets + " (" + over_str_2 + ")";
    document.getElementById("team2_score").innerHTML = team_score[1];
    document.getElementById("match_additional_details").innerHTML = scorecard.match_additional_details[0];
    document.getElementById('bowler').innerHTML = scorecard.bowler;
    document.getElementById('batsman1').innerHTML = scorecard.batsmen[0];
    document.getElementById('batsman2').innerHTML = scorecard.batsmen[1];
    document.getElementById('current-over-id').innerHTML = scorecard.teams[scorecard.innings - 1]+" | " +team_score[scorecard.innings - 1];
    document.getElementById("current-over-container")
            .appendChild(create_current_over_balls_container(scorecard.over_id, scorecard.this_over));
    document.getElementById('this_over_summary').innerHTML =
        "Over " + (scorecard.innings === 1 ? over_str_1 : over_str_2)+"  :  " + scorecard.this_over_summary;

    document.getElementById('crr').innerHTML = crr !== 0 ? ("Cur. RR : "+ crr.toFixed(2)) : "";
    document.getElementById('rrr').innerHTML = rrr !== 0 ? ("Req. RR : "+ rrr.toFixed(2)) : "";

    document.getElementById('partnership').innerHTML = 'Partnership : ' + scorecard.partnership.replaceAll(' Runs, ', ' (').replaceAll(' B', ')');
    document.getElementById('last_batsman').innerHTML = 'L. Wkt.: ' +scorecard.last_batsman + " " +scorecard.last_wicket_at;
    document.getElementById('timer').innerHTML = "&nbsp";
    scorecard_time = 0;
    console.log("Scorecard Updated");
}
function fill_balance(){
    setTimeout(function (){
        const xmlhttp = new XMLHttpRequest();
        xmlhttp.onload = function () {
            if (this.readyState === 4 && this.status === 200) {
                let balance = JSON.parse(this.responseText);
                document.getElementById('balance').innerHTML = "Balance &#8377;"+balance.balance;
            }
        };
        xmlhttp.open("GET", "https://om8zdfeo2h.execute-api.ap-south-1.amazonaws.com/get_user_balance/" + this.getCookie('user_ref_id'), true);
        xmlhttp.send();
    }, 1000);
}
function create_current_over_balls_container(over_id, this_over){
    document.getElementById('current-over-container').childNodes.forEach((child) => {
        child.remove();
    });
    document.getElementById('current-over-container').setAttribute('name', 'over');
    document.getElementById('current-over-container').setAttribute('over_id', over_id);
    let res = document.createElement("div");
    res.setAttribute("style","display: flex");

    let width = this_over.length < 4 ? 33.3 : 100 / this_over.length;
    for(let i=0;i<this_over.length;i++) {
        const div = document.createElement("div");
        div.classList.add('balls');
        if(this_over[i].includes('W'))
            div.classList.add('ball-wicket');
        else if(this_over[i].includes('nb') || this_over[i].includes('w') || this_over[i].includes('lb'))
            div.classList.add('ball-extra');
        else if(this_over[i].includes('4'))
            div.classList.add('ball-four');
        else if(this_over[i].includes('6'))
            div.classList.add('ball-six');
        div.setAttribute("style", "align-content: center;width: "+width+"%;")

        const span = document.createElement("span");
        span.innerHTML = this_over[i];
        div.appendChild(span);

        res.appendChild(div);
    }
    return res;
}
function get_valid_balls(this_over){
    let count = 0;
    for (let i=0;i<this_over.length;i++){
        if(this_over[i].includes('w') || this_over[i].includes('nb'))
            continue;
        count++;
    }
    return count;
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
function fill_profile(){
    const xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (this.readyState === 4 && this.status === 200) {
            document.getElementById("profile").innerHTML = this.responseText;
        }
    };
    xmlhttp.open("GET", "../model_ui/profile.php", true);
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
function add_new_over_data() {
    let existing_overs_element = document.getElementsByName("over");
    if (existing_overs_element.length < 4) {
        let last_showing_over_id = Number(existing_overs_element.item(existing_overs_element.length - 1).getAttribute("over_id"));
        let series_id = getCookie("series_id");
        let match_id = getCookie("match_id");
        const xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function () {
            if (this.readyState === 4 && this.status === 200) {
                let scorecard = JSON.parse(this.responseText);
                document.getElementById("previous-over-container")
                    .appendChild(create_new_over_element(scorecard.this_over, scorecard.over_id));
            }
        };
        xmlhttp.open("GET", "https://om8zdfeo2h.execute-api.ap-south-1.amazonaws.com/scores/" + series_id + "/" + match_id + "/" + (last_showing_over_id - 1), true);
        xmlhttp.send();
    } else {
        if(document.getElementsByName("no-more-overs").length === 0)
            document.getElementById("previous-over-container").appendChild(create_no_more_overs());
    }
}
function create_new_over_element(this_over, over_id) {
    const div = document.createElement("div");
    div.setAttribute("class", "balls-container-extra");
    div.setAttribute("name", "over");
    div.setAttribute("id", over_id);
    div.setAttribute('over_id', over_id);

    const more_over_div = document.createElement("div");
    more_over_div.setAttribute("class", "more_over_div");
    more_over_div.innerHTML = "Over " + (over_id % 100);

    div.appendChild(more_over_div);

    let ball_id = 1;
    this_over.forEach((ball) => {
        const ball_div = document.createElement("div");
        ball_div.classList.add('balls-extra');
        if(ball.includes('W'))
            ball_div.classList.add('ball-wicket');
        else if(ball.includes('nb') || ball.includes('w') || ball.includes('lb'))
            ball_div.classList.add('ball-extra');
        else if(ball.includes('4'))
            ball_div.classList.add('ball-four');
        else if(ball.includes('6'))
            ball_div.classList.add('ball-six');
        let width = this_over.length < 4 ? 33.333 : 100 / this_over.length;
        ball_div.setAttribute("style", "width: "+width+"%; align-content: center");

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
function update_session_slot_details(session, room) {
    slots_time = 0;
    update_session_slot_details_actual(session, room, true);
    update_slots_timer = setInterval(function (){
        slots_time += 1;
        if(slots_time > 5)
            document.getElementById('timer_slots').innerHTML = "Updated "+slots_time+" sec ago";
        else
            document.getElementById('timer_slots').innerHTML = "&nbsp";
    }, 1000);
    slots_timer = setInterval(function (){
        if (slots_time > 4)
            update_session_slot_details_actual(session, room, false);
    }, 6000);
}
function update_session_slot_details_actual(session, room, update_selected){
    let series_id = this.getCookie('series_id');
    let match_id = this.getCookie('match_id');
    let amount = document.getElementById('amount').value;
    if (session === 'winner' || session === 'special')
        return false;
    const xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function () {
        if (this.readyState === 4 && this.status === 200) {
            let responseText = '{'+this.responseText.split('{')[1];
            let bid_master = JSON.parse(responseText);
            if (bid_master.hasOwnProperty('error')) {
                alert("Session Closed For Biding. !!")
                if (window.location.hostname.includes('localhost'))
                    window.location.href = "http://localhost/t20/matches/match.php?" +
                        "match_id=" + getCookie('match_id') + "&series_id=" + getCookie('series_id') + "&match_name=" + getCookie('match_name');
                else
                    window.location.href = "https://www.crickett20.in/T20/matches/match.php?" +
                        "match_id=" + getCookie('match_id') + "&series_id=" + getCookie('series_id') + "&match_name=" + getCookie('match_name');
            }
            document.getElementById("slot_a_runs").innerHTML =
                "Runs 0 to " + (bid_master.predicted_runs_a - 1);
            document.getElementById('slot_a_runs_1').innerHTML = "(Max "+(bid_master.predicted_runs_a - bid_master.runs - 1)+" runs in "+bid_master.balls_left+" balls)";
            document.getElementById("slot_a_amount").innerHTML = "Put &#8377;" + amount +
                " & Take &#8377;" + Math.trunc(amount * bid_master.rate_1);

            document.getElementById("slot_b_runs").innerHTML =
                "Runs [" + bid_master.predicted_runs_a + " to " + bid_master.predicted_runs_b + "]";
            document.getElementById('slot_b_runs_1').innerHTML = "("+(bid_master.predicted_runs_a - bid_master.runs)+" to "+(bid_master.predicted_runs_b - bid_master.runs)+" runs in "+bid_master.balls_left+" balls)";
            document.getElementById("slot_b_amount").innerHTML = "Put &#8377;" + amount +
                " & Take &#8377;" + Math.trunc(amount * bid_master.rate_2);

            document.getElementById("slot_c_runs").innerHTML =
                "Runs " + (bid_master.predicted_runs_b + 1) + " or More";
            document.getElementById('slot_c_runs_1').innerHTML = "(Min "+(bid_master.predicted_runs_b - bid_master.runs + 1) +" runs in "+bid_master.balls_left+" balls)";
            document.getElementById("slot_c_amount").innerHTML = "Put &#8377;" + amount +
                " & Take &#8377;" + Math.trunc(amount * bid_master.rate_3);

            let slot_a = document.getElementById("slot_a");
            let slot_b = document.getElementById("slot_b");
            let slot_c = document.getElementById("slot_c");
            if(update_selected) {
                let max_rate = Math.max(bid_master.rate_1, bid_master.rate_2, bid_master.rate_3);
                if (bid_master.rate_3 === max_rate)
                    slot_c.checked = true;
                if (bid_master.rate_2 === max_rate)
                    slot_b.checked = true;
                if (bid_master.rate_1 === max_rate)
                    slot_a.checked = true;
            }
            document.getElementById('timer_slots').innerHTML = "&nbsp";
            slots_time = 0;
            console.log("Slots Updated");
        }
    };
    xmlhttp.open("GET", "../matches/GetSessionSlotDetails.php?series_id=" + series_id + "&match_id=" + match_id + "&session=" + session + "&room=" + room + "&amount=" + amount, true);
    xmlhttp.send();
}
function update_winner_slot_details(session, room){
    slots_time = 0;
    update_winner_slot_details_actual(session, room, true);
    update_slots_timer = setInterval(function (){
        slots_time += 1;
        if(slots_time > 5)
            document.getElementById('timer_slots').innerHTML = "Updated "+slots_time+" sec ago";
        else
            document.getElementById('timer_slots').innerHTML = "&nbsp";
    }, 1000);
    slots_timer = setInterval(function (){
        if (slots_time > 4)
            update_winner_slot_details_actual(session, room, false);
    }, 6000);
}
function update_winner_slot_details_actual(session, room, update_checked) {
    let series_id = this.getCookie('series_id');
    let match_id = this.getCookie('match_id');
    let amount = document.getElementById('amount').value;
    const xmlhttp = new XMLHttpRequest();
    if(session === 'winner') {
        xmlhttp.onreadystatechange = function () {
            if (this.readyState === 4 && this.status === 200) {
                let responseText = '{' + this.responseText.split('{')[1]
                let bid_master = JSON.parse(responseText);
                document.getElementById("winner_a").innerHTML =
                    bid_master.team_a + " Wins";
                document.getElementById("winner_a_amount").innerHTML = "Put &#8377;" + amount +
                    " Take &#8377;" + Math.trunc(amount * bid_master.rate_1);

                document.getElementById("winner_b").innerHTML =
                    bid_master.team_b + " Wins";
                document.getElementById("winner_b_amount").innerHTML = "Put &#8377;" + amount +
                    " Take &#8377;" + Math.trunc(amount * bid_master.rate_2);

                let slot_a = document.getElementById("slot_a");
                let slot_b = document.getElementById("slot_b");
                if (update_checked) {
                    let max_rate = Math.max(bid_master.rate_1, bid_master.rate_2);
                    if (bid_master.rate_2 === max_rate)
                        slot_b.checked = true;
                    if (bid_master.rate_1 === max_rate)
                        slot_a.checked = true;
                }
                console.log("Winner Slots Updated");
                slots_time = 0;
            }
        };
        xmlhttp.open("GET", "../matches/GetWinnerSlotDetails.php?series_id=" + series_id + "&match_id=" + match_id + "&room=" + room + "&amount=" + amount, true);
        xmlhttp.send();
    }
}
function increase_amount(session, room, amount){
    let amount_element = document.getElementById("amount");
    amount_element.value = parseInt(amount_element.value) + parseInt(amount);
    document.getElementById('amount-display').innerHTML = "Amount of &#8377;" + amount_element.value;
    updateAmount();
    onRelease(session, room);
}
function decrease_amount(session, room, amount){
    let amount_element = document.getElementById("amount");
    amount_element.value -= amount;
    document.getElementById('amount-display').innerHTML = "Amount of &#8377;" + amount_element.value;
    updateAmount();
    onRelease(session, room);
}
document.addEventListener('click', function(e) {
    let sidebar = document.getElementById('side-bar-container');
    const sidebarIcon = document.getElementById('side-bar-icon');
    if (!sidebar.contains(e.target) && !sidebarIcon.contains(e.target)) {
        w3_close();
    }
});
function w3_open() {
    if(parseInt(this.getCookie('user_ref_id')) > 0 && this.getCookie('user_type').length > 0)
        document.getElementById("side-bar-container").style.display = "block";
}

function w3_close() {
    if(parseInt(this.getCookie('user_ref_id')) > 0 && this.getCookie('user_type').length > 0)
        document.getElementById("side-bar-container").style.display = "none";
}
function settle_bid(bid_id, type, session){
    if (window.location.hostname.includes('localhost')) {
        alert("Cannot perform action from localhost.");
    }else {
        const userResponse = prompt("Type WIN or LOSS as input.", "LOSS");
        let execute = false;
        if (userResponse != null && userResponse.toLowerCase() === 'win') {
            execute = true;
        }
        if(userResponse != null && userResponse.toLowerCase() === 'loss') {
            execute = true
        }
        if(execute) {
            const xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function () {
                if (this.readyState === 4 && this.status === 200) {
                    console.log(this.responseText);
                    if (window.location.hostname.includes('localhost'))
                        window.location.href = "http://localhost/t20/admin/admin_match_"+type+"_dashboard.php?session=" + session;
                    else
                        window.location.href = "https://www.crickett20.in/T20/admin/admin_match_"+type+"_dashboard.php?session=" + session;
                }
            };
            xmlhttp.open("GET", "https://om8zdfeo2h.execute-api.ap-south-1.amazonaws.com/settle_bid/"+type+"/" + bid_id + "/" + userResponse.toLowerCase(), true);
            xmlhttp.send();
        }
    }
}
function redirect_to_home(){
    if (window.location.hostname.includes('localhost'))
        window.location.href = "http://localhost/t20/";
    else
        window.location.href = "https://www.crickett20.in/T20/";
}
setTimeout(function (){
    clearInterval(scorecard_timer);
    clearInterval(update_scorecard_timer);
    clearInterval(slots_timer);
    clearInterval(update_slots_timer);
    console.log("All background timers cleared.");
}, 300000);
function place_bid_text(){
    let place_bid_btn = document.getElementById('place_bid');
    place_bid_btn.style.color = 'black';
    place_bid_btn.style.background = 'wheat';
    place_bid_btn.value = "Placing Bid . . . ";
    place_bid_btn.disabled = true;
    document.getElementById("bid_form").submit();
}
function updateAmount(){
    document.getElementById("amount-display").innerHTML = "Amount of &#8377;" + document.getElementById("amount").value;
    let slider = document.getElementById('amount');
    let min = slider.min;
    let value = slider.value - min;
    let max = slider.max - min;
    let percentage = (value / max) * 100;
    slider.style.background = `linear-gradient(to right, #4CAF50 ${percentage}%, #ddd ${percentage}%)`;
}
function onRelease(session, room) {
    if (session.length === 2)
        update_session_slot_details_actual(session, room, false)
    else if (session === 'winner')
        update_winner_slot_details_actual(session, room, false);
}