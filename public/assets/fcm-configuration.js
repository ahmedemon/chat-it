// Initialize Firebase app
var firebaseConfig = {
    apiKey: "AIzaSyBQnYmtdYZKGC2wpmOi8gTtYtEuWFyOdzU",
    authDomain: "live-chat-afb6f.firebaseapp.com",
    projectId: "live-chat-afb6f",
    storageBucket: "live-chat-afb6f.appspot.com",
    messagingSenderId: "382063638706",
    appId: "1:382063638706:web:ff8e335940ac3b369031ab",
    measurementId: "G-4EPL3SC3G4"
};

firebase.initializeApp(firebaseConfig);

// Initialize Firebase messaging
const messaging = firebase.messaging();

messaging.requestPermission()
    .then(function() {
        console.log("Notification permission granted.");
        return messaging.getToken();
    })
    .then(function(token) {
        console.log("FCM Token:", token);
        // Send the token to your server for sending notifications
    })
    .catch(function(error) {
        console.log("Unable to get permission or token:", error);
    });


function sendNotification() {
    const title = "Test Notification";
    const options = {
        body: "This is a test notification from FCM.",
        icon: "/path/to/icon.png"
    };

    messaging
        .getToken()
        .then(function(currentToken) {
            if (currentToken) {
                fetch("https://fcm.googleapis.com/fcm/send", {
                        method: "POST",
                        headers: {
                            Authorization: "key=YOUR_SERVER_KEY",
                            "Content-Type": "application/json"
                        },
                        body: JSON.stringify({
                            notification: {
                                title: title,
                                body: options.body,
                                icon: options.icon
                            },
                            to: currentToken
                        })
                    })
                    .then(function(response) {
                        console.log("Notification sent successfully:", response);
                    })
                    .catch(function(error) {
                        console.log("Failed to send notification:", error);
                    });
            } else {
                console.log("No FCM token available.");
            }
        })
        .catch(function(error) {
            console.log("Failed to get FCM token:", error);
        });
}

