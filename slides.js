var announcements = [];
$(
    function () {
        $(document).ready(getAnnouncements);
        window.setInterval(
            function() {
                dispAnnouncement();
            },
            15000
        );
    }
);

function getAnnouncements() {
    jQuery.get(
        "api.php",
        {"action": "get_last_announcements", "num": 10},
        function (data, status) {
            for (var i = 0; i < data["announcements"].length; i++) {
                announcements.push(data["announcements"][i]);
            }
            dispAnnouncement();
        },
        "json"
    );
}
function format_date(datestring) {
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

function dispAnnouncement() {
    var announcement = announcements[Math.floor(Math.random() * announcements.length)];
    var announcementDate = announcement["date"];
    format_date(announcementDate);

    $(".slide-header").text(announcement["title"]);
    $(".slide-body").text(announcement["text"]);
    $(".slide-id").text(announcement["id"]);
}
