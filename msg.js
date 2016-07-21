$(
    function () {
        $("#msg").click(
            function () {
                msg("hide");
            }
        );
    }
);
function msg(color="hide", message="")
{
    $("#msg").removeClass();
    switch (color) {
    case "red":
        $("#msg").addClass("msg-red");
        $("#msg").animate({height: "25px"}, "fast");
            break;
    case "green":
        $("#msg").addClass("msg-green");
        $("#msg").animate({height: "25px"}, "fast");
            break;
    case "hide":
        $("#msg").addClass("msg-hidden");
        $("#msg").height(0);
    default:
        $("#msg").addClass("msg-hidden");
        $("#msg").height(0);
            break;
    }
    $("#msg").text(message);
}
