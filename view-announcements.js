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
    //I haven't gotten a date library yet. 
    var announcementDate = announcement["date"];
    var d = new Date(announcementDate);
    d.setHours(d.getHours() - 5);
    var dateOptions = {
        weekday: "long", year: "numeric", month: "long", day: "numeric", 
        hour: "2-digit", minute: "2-digit", second:"2-digit"
    };
    var datestring = (d.toLocaleString("en-US", dateOptions));
    
    $("#announcements").append(
        `
        <div class="announcement">
            <div class="announcement-header">
                ${announcement["title"]}
            </div>
            <div class="announcement-body">
                ${announcement["text"]}
            </div>
            <div class="announcement-footer">
                Announcement ID: ${announcement["id"]}
                <div style="float:right">
                    ${datestring}
                </div>
            </div>
        </div>
        `
    );
}
