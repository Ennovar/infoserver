$(
    function () {
        $("#announcementForm").submit(
            function () {
                newAnnouncement();
                return false;
            }
        );
    }
);
function newAnnouncement()
{
    title=$("#title").val();
    text=$("#text").val();

    jQuery.get(
        "api.php",
        {"action":"new_announcement", "title":title, "text":text},
        function (data, status) {
            if (data["success"] == true) {
                msg("green", "Post submitted successfully"); } else {
                msg("red", data["message"]); }
        },
        "json"
    );
    
}
