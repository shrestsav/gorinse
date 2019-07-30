import firebase from 'firebase/app'
import 'firebase/firestore'

const firebaseConfig = {
	apiKey: "AIzaSyDcNh9UE4Lu1m2T8K3ITQTa0GXz4w6TBOE",
	authDomain: "gorinse-478d7.firebaseapp.com",
	databaseURL: "https://gorinse-478d7.firebaseio.com",
	projectId: "gorinse-478d7",
	storageBucket: "gorinse-478d7.appspot.com",
	messagingSenderId: "754016098809",
	appId: "1:754016098809:web:09dff06f294dc34e"
};

const firebaseApp = firebase.initializeApp(firebaseConfig);

var db = firebaseApp.firestore();

export default db
