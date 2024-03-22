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
    let x = 5;
    setInterval(function (){
        document.getElementById('cancel_bid').innerText = 'Cancel this bid ('+x+' seconds remaining)';
        x--;
        if (x < 0)
            window.location.href = "https://www.crickett20.in/T20/matches/over.php?match_id="+match_id+"&innings="+innings+"&over="+over;
    }, 1000);
}