<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signaling</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <script>
        function sendOffer(offer) {
            $.post('./call_process.php', {
                action: 'offer',
                offer: offer
            });
        }

        function sendAnswer(answer) {
            $.post('./call_process.php', {
                action: 'answer',
                answer: answer
            });
        }

        function sendCandidate(candidate) {
            $.post('./call_process.php', {
                action: 'candidate',
                candidate: candidate
            });
        }

        function getOffer(peerId) {
            $.post('./call_process.php', {
                action: 'get_offer',
                peer_id: peerId
            }, function(data) {
                console.log('Received offer:', data);
            });
        }

        function getAnswer(peerId) {
            $.post('./call_process.php', {
                action: 'get_answer',
                peer_id: peerId
            }, function(data) {
                console.log('Received answer:', data);
            });
        }

        function getCandidates(peerId) {
            $.post('./call_process.php', {
                action: 'get_candidates',
                peer_id: peerId
            }, function(data) {
                console.log('Received candidates:', data);
            });
        }
    </script>
</body>
</html>
