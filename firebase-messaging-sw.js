importScripts('https://www.gstatic.com/firebasejs/8.10.0/firebase-app.js');
importScripts('https://www.gstatic.com/firebasejs/8.10.0/firebase-messaging.js');

// Initialize Firebase
firebase.initializeApp({
    apiKey: "AIzaSyBQnYmtdYZKGC2wpmOi8gTtYtEuWFyOdzU",
    authDomain: "live-chat-afb6f.firebaseapp.com",
    projectId: "live-chat-afb6f",
    storageBucket: "live-chat-afb6f.appspot.com",
    messagingSenderId: "382063638706",
    appId: "1:382063638706:web:ff8e335940ac3b369031ab",
    measurementId: "G-4EPL3SC3G4"
});

const messaging = firebase.messaging();
