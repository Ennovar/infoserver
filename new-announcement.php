<html>
    <head>
        <?php require("links.php"); ?>
        <script src="new-announcement.js"></script>
    </head>
    <body>
        <div id="msg" class="msg-hidden"></div>
        <div class="centred">
            <form id="announcementForm">
                Announcement Title:
                <input class="blue-tbox" type="text" id="title">
                Announcement Text:
                <textarea class="blue-tbox" id="text"> </textarea>
                <input class="submit float-right" type="submit" value="New Announcement">
            </form>
        </div>
    </body>
</html>
