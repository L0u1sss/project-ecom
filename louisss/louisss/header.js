console.log("headder connected");
oncart()
function oncart() {
    var settings = {
        "url": "http://localhost/php-api/index.php/api/User3/headercart",
        "method": "POST",
        "timeout": 0,
        "headers": {
            "X-Access-Token": localStorage.getItem("token")
        },
    };

    $.ajax(settings).done(function (response) {
        console.log(response.amount);
        if (response.status == true) {
            let amount = response.amount;
            document.getElementById("amount").innerHTML = +amount;
            document.getElementById("amountMobile").innerHTML = +amount;
        }
    });
}