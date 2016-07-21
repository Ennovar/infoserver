$(
    function () {
        $(document).ready(getAnnouncements);
    }
);

function getAnnouncements() {
    jQuery.get(
        "api.php",
        {"action": "get_last_announcements", "num": 10},
        function (data, status) {
            for (var i = 0; i < data["announcements"].length; i++) {
                addAnnouncement(data["announcements"][i]);
            }
        },
        "json"
    );
}

function addAnnouncement(announcement) {
    $("#announcements").append(
        `
        <div class="announcement">
            <div class="announcement-header">
                ${announcement["title"]}
            </div>
            <div class="announcement-body">
                ${announcement["text"]}
            </div>
        </div>
        `
    );
}
