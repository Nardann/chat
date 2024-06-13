<?php
include('../includes/auth.php');
redirectIfNotLoggedIn();
include('../config/config.php');

$username = $_SESSION['username'];
$userId = $_SESSION['user_id'];

$sql = "SELECT username FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $friend_id);
$stmt->execute();
$result = $stmt->get_result();
$friend_username = $result->fetch_assoc()['username'];
$stmt->close();

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
        #friendsList {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <h2>Appel Vidéo</h2>

    <div id="friendsList">
        <h3>Liste de vos amis</h3>
        <?php if (empty($friends)): ?>
            <p>Vous n'avez aucun ami.</p>
        <?php else: ?>
            <?php foreach ($friends as $friend): ?>
                <p>
                    <span><?php echo htmlspecialchars($friend['username']); ?></span>
                    <button onclick="startCallWithFriend(<?php echo $friend['id']; ?>, '<?php echo htmlspecialchars($friend['username']); ?>')">Appeler</button>
                </p>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <video id="localVideo" autoplay playsinline></video>
    <video id="remoteVideo" autoplay playsinline></video>
    <br>
    <button id="endCall">Terminer l'appel</button>
    <input type="hidden" id="peerId" value="">

    <script>
        const userId = <?php echo $_SESSION['user_id']; ?>;
        const peerIdInput = document.getElementById('peerId');

        let localStream;
        let remoteStream;
        let peerConnection;

        const endCallButton = document.getElementById('endCall');
        const localVideo = document.getElementById('localVideo');
        const remoteVideo = document.getElementById('remoteVideo');

        const servers = {
            iceServers: [
                { urls: 'stun:stun.l.google.com:19302' }
            ]
        };

        function startCallWithFriend(peerId, peerUsername) {
            peerIdInput.value = peerId;
            navigator.mediaDevices.getUserMedia({ video: true, audio: true }).then(stream => {
                localStream = stream;
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

                peerConnection.createOffer().then(offer => {
                    return peerConnection.setLocalDescription(offer);
                }).then(() => {
                    sendSignalingData('offer', peerConnection.localDescription);
                });

                pollSignalingData('answer', async (data) => {
                    await peerConnection.setRemoteDescription(new RTCSessionDescription(data));
                });

                pollSignalingData('candidate', async (data) => {
                    await peerConnection.addIceCandidate(new RTCIceCandidate(data));
                });
            }).catch(error => {
                console.error('Error accessing media devices.', error);
            });
        }

        function sendSignalingData(action, data) {
            fetch('signal.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `action=${action}&${action}=${JSON.stringify(data)}&peer_id=${peerIdInput.value}`
            });
        }

        function pollSignalingData(type, callback) {
            setInterval(async () => {
                const response = await fetch('signal.php', {
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
            if (peerConnection) {
                peerConnection.close();
            }
            if (localStream) {
                localStream.getTracks().forEach(track => track.stop());
            }
            remoteVideo.srcObject = null;
        };
    </script>
</body>
</html>

<?php include('../includes/footer.php'); ?>
