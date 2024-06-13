<?php
include('../includes/auth.php');
redirectIfNotLoggedIn();
include('../config/config.php');
include('../includes/header.php');
include('../includes/navbar.php');
$username = $_SESSION['username'];
$userId = $_SESSION['user_id'];

$username = $_SESSION['username'];
$sql = "SELECT id FROM users WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$user_id = $user['id'];

$sql = "SELECT users.id, users.username FROM friendships 
        JOIN users ON friendships.friend_id = users.id 
        WHERE friendships.user_id = ? AND friendships.status = 'accepted'
        UNION
        SELECT users.id, users.username FROM friendships 
        JOIN users ON friendships.user_id = users.id 
        WHERE friendships.friend_id = ? AND friendships.status = 'accepted'";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $user_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

echo "<h2>Your Friends</h2>";
while ($row = $result->fetch_assoc()) {
    $friend_id = $row['id'];
    $friend_username = htmlspecialchars($row['username']); // Échappement du nom d'utilisateur

    echo "<input type='hidden' name='friend_id' value='$friend_id'>";
    echo "<p>$friend_username <button onclick=\"startCallWithFriend($friend_id, '$friend_username')\">Appeler</button></p>";
}


$stmt->close();



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
                <?php foreach ($friends as $row): ?>
                    <?php
                    $friend_id = $row['id'];
                    $friend_username = $row['username'];
                    ?>
                    <p>
                        <span><?php echo htmlspecialchars($friend_username); ?></span>
                        <button onclick="startCallWithFriend(<?php echo $friend_id; ?>, '<?php echo htmlspecialchars($friend_username); ?>')">Appeler</button>
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
            fetch('./call_process.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `action=${action}&${action}=${JSON.stringify(data)}&peer_id=${peerIdInput.value}`
            });
        }

        function pollSignalingData(type, callback) {
            setInterval(async () => {
                const response = await fetch('./call_process.php', {
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
