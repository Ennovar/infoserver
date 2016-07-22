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

function dispAnnouncement() {
    var announcement = announcements[Math.floor(Math.random() * announcements.length)];
    var announcementDate = announcement["date"];
    var d = new Date(announcementDate);
    d.setHours(d.getHours() - 5);
    var dateOptions = {
        weekday: "long", year: "numeric", month: "long", day: "numeric", 
        hour: "2-digit", minute: "2-digit", second:"2-digit"
    };
    var datestring = (d.toLocaleString("en-US", dateOptions));

    $(".slide-header").text(announcement["title"]);
    $(".slide-body").text(announcement["text"]);
    $(".slide-footer").html(
        `
        Announcement ID: ${announcement["id"]}
        <span style="float:right; vertical-align: middle">
            ${datestring}
        </span>
        `
    );
}
