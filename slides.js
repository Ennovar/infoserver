var announcements = [];
var announcementIndex = 0;
$(
    function () {
        $(document).ready(getAnnouncements);
        window.setInterval(
            function () {
                if ($(".screensaver").hasClass("hidden")) {
                    dispAnnouncement();
                }
            },
            20000
        );
        window.setInterval(
            function() {
                save_screen();
            },
            60000
        );
    }
);

function getAnnouncements()
{
    jQuery.get(
        "api.php",
        {"action": "get_last_announcements", "num": 5},
        function (data, status) {
            for (var i = 0; i < data["announcements"].length; i++) {
                announcements.push(data["announcements"][i]);
            }
            dispAnnouncement();
        },
        "json"
    );
}
function format_date(datestring)
{
    jQuery.get(
        "api.php",
        {"action": "format_date", "date": datestring},
        function (data, status) {
            result = data["datestring"];
            $(".slide-date").text(result);
        },
        "json"
    );
}

function save_screen()
{
    jQuery.get(
        "api.php",
        {"action": "image_url"},
        function (data, status) {
            $(".screensaver").attr("src", data["image"]);
            $(".screensaver").removeClass("hidden");
            setTimeout(
                function() {
                    $(".screensaver").addClass("hidden");
                },
                60000
            );
        },
        "json"
    );
}


function dispAnnouncement()
{
    var announcement = announcements[announcementIndex++ % announcements.length];
    var announcementDate = announcement["date"];
    format_date(announcementDate);

    $(".slide-header").text(announcement["title"]);
    $(".slide-text").text(announcement["text"]);
    $(".slide-author").text((announcement["announcer"] ? announcement["announcer"] : "Anonymous"));
    $(".slide-id").text(announcement["id"]);
}
