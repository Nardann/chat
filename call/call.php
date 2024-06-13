<?php
include('../includes/auth.php');
redirectIfNotLoggedIn();
include('../config/config.php');

$username = $_SESSION['username'];
$userId = $_SESSION['user_id'];

include('../includes/header.php');
include('../includes/navbar.php');
?>

<!DOCTYPE html>
<html>
<head>
    <title>Appel Vidéo</title>
    <style>
        video {
            width: 300px;
            height: 300px;
        }
    </style>
</head>
<body>
    <h2>Appel Vidéo</h2>
    <video id="localVideo" autoplay playsinline></video>
    <video id="remoteVideo" autoplay playsinline></video>
    <br>
    <button id="startCall">Démarrer l'appel</button>
    <button id="endCall">Terminer l'appel</button>
    <input type="hidden" id="peerId" value="">

    <script>
        const userId = <?php echo $_SESSION['user_id']; ?>;
        const peerIdInput = document.getElementById('peerId');

        let localStream;
        let remoteStream;
        let peerConnection;

        const startCallButton = document.getElementById('startCall');
        const endCallButton = document.getElementById('endCall');
        const localVideo = document.getElementById('localVideo');
        const remoteVideo = document.getElementById('remoteVideo');

        const servers = {
            iceServers: [
                { urls: 'stun:stun.l.google.com:19302' }
            ]
        };

        startCallButton.onclick = async () => {
            const peerId = prompt("Enter the peer ID to call:");
            peerIdInput.value = peerId;
            localStream = await navigator.mediaDevices.getUserMedia({ video: true, audio: true });
            localVideo.srcObject = localStream;

            peerConnection = new RTCPeerConnection(servers);
            peerConnection.addStream(localStream);

            peerConnection.onaddstream = (event) => {
                remoteVideo.srcObject = event.stream;
            };

            peerConnection.onicecandidate = (event) => {
                if (event.candidate) {
                    sendSignalingData('candidate', event.candidate);
                }
            };

            const offer = await peerConnection.createOffer();
            await peerConnection.setLocalDescription(offer);
            sendSignalingData('offer', offer);

            pollSignalingData('answer', async (data) => {
                await peerConnection.setRemoteDescription(new RTCSessionDescription(data));
            });

            pollSignalingData('candidate', async (data) => {
                await peerConnection.addIceCandidate(new RTCIceCandidate(data));
            });
        };

        function sendSignalingData(action, data) {
            fetch('call_process.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `action=${action}&${action}=${JSON.stringify(data)}&peer_id=${peerIdInput.value}`
            });
        }

        function pollSignalingData(type, callback) {
            setInterval(async () => {
                const response = await fetch('call_process.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `action=get_${type}&peer_id=${userId}`
                });
                const data = await response.json();
                if (data) {
                    callback(data);
                }
            }, 1000);
        }

        endCallButton.onclick = () => {
            peerConnection.close();
            localStream.getTracks().forEach(track => track.stop());
            remoteVideo.srcObject = null;
        };
    </script>
</body>
</html>

<?php include('../includes/footer.php'); ?>
