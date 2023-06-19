// Initialize Firebase app
var firebaseConfig = {
    apiKey: "AIzaSyBQnYmtdYZKGC2wpmOi8gTtYtEuWFyOdzU",
    authDomain: "live-chat-afb6f.firebaseapp.com",
    projectId: "live-chat-afb6f",
    storageBucket: "live-chat-afb6f.appspot.com",
    messagingSenderId: "382063638706",
    appId: "1:382063638706:web:ff8e335940ac3b369031ab",
    measurementId: "G-4EPL3SC3G4",
};

firebase.initializeApp(firebaseConfig);

// Initialize Firebase messaging
const messaging = firebase.messaging();
var serverKey =
    "key=AAAAWPTEeLI:APA91bFrXmdxu5T8eA6wZ1mPR797Kw1Rs9ae58rLPfgoWcoSxmO_TxgnP8abk2L3r86UBDGGHPkMGwmTsqWSH0romuMocuC7amCP-lr1d_XnKo46_TpNm5AsYfBax9xCGtNz-IbH5VQm";
messaging
    .requestPermission()
    .then(function () {
        console.log("Notification permission granted.");
        return messaging.getToken();
    })
    .then(function (token) {
        console.log("FCM Token:", token);
        // Send the token to your server for sending notifications
    })
    .catch(function (error) {
        console.log("Unable to get permission or token:", error);
    });

function sendNotification() {
    const title = $("#conversation_id").val();
    var body = $("#message_body").val();
    var icon = "https://support.softhostit.com/public/assets/chat-head.png";
    const options = {
        body: body,
        icon: icon,
    };

    messaging
        .getToken()
        .then(function (currentToken) {
            if (currentToken) {
                fetch("https://fcm.googleapis.com/fcm/send", {
                    method: "POST",
                    headers: {
                        Authorization: serverKey,
                        "Content-Type": "application/json",
                    },
                    body: JSON.stringify({
                        to: "/topics/all",
                        notification: {
                            title: title,
                            body: options.body,
                            image: options.icon,
                        },
                    }),
                })
                    .then(function (response) {
                        console.log(
                            "Notification sent successfully:",
                            response
                        );
                    })
                    .catch(function (error) {
                        console.log("Failed to send notification:", error);
                    });
            } else {
                console.log("No FCM token available.");
            }
        })
        .catch(function (error) {
            console.log("Failed to get FCM token:", error);
        });
}
