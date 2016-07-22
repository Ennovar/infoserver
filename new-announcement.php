<html>
    <head>
        <?php require("links.php"); ?>
        <script src="new-announcement.js"></script>
    </head>
    <body>
        <?php require("nav.php"); ?>
        <div id="msg" class="msg-hidden"></div>
        <div class="container">
        <div class="centred col-md-8 col-md-offset-2">
            <form id="announcementForm">
                Announcement Title:
                <input class="blue-tbox" type="text" id="title">
                Announcer:
                <input class="blue-tbox" type="text" id="announcer">
                Announcement Text:
                <textarea class="blue-tbox" id="text" rows=33> </textarea>
                <input class="submit float-right" type="submit" value="New Announcement">
            </form>
        </div>
        </div>
    </body>
</html>
