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
            $.post('path/to/your_php_file.php', {
                action: 'offer',
                offer: offer
            });
        }

        function sendAnswer(answer) {
            $.post('path/to/your_php_file.php', {
                action: 'answer',
                answer: answer
            });
        }

        function sendCandidate(candidate) {
            $.post('path/to/your_php_file.php', {
                action: 'candidate',
                candidate: candidate
            });
        }

        function getOffer(peerId) {
            $.post('path/to/your_php_file.php', {
                action: 'get_offer',
                peer_id: peerId
            }, function(data) {
                console.log('Received offer:', data);
            });
        }

        function getAnswer(peerId) {
            $.post('path/to/your_php_file.php', {
                action: 'get_answer',
                peer_id: peerId
            }, function(data) {
                console.log('Received answer:', data);
            });
        }

        function getCandidates(peerId) {
            $.post('path/to/your_php_file.php', {
                action: 'get_candidates',
                peer_id: peerId
            }, function(data) {
                console.log('Received candidates:', data);
            });
        }
    </script>
</body>
</html>
