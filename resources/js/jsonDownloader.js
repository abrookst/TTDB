$(document).ready(()=> {

    document.getElementById('export').onclick = function () {

        // Validate deck
        var invalid = false;
        $('.yourDeck div').each(function () {
            if ($(this).find('img').length) {
                // do nothing
            } else {
                alert("Not enough cards in deck!");
                invalid = true;
                return false;
            }
        });
        if (invalid) {
            return false;
        }

        // Check for valid deck name
        var name = document.getElementById("dName").value;
        const illegal = ["'\'", "/", ":", "*", '"', "<", ">", "|", ","];
        for (var i = 0; i < illegal.length; ++i) {
            if (name.includes(illegal[i])) {
                alert('Deck cannot have any of these characters: \\ / : * " < > | ,');
                return false;
            }
        }
        if (name == "") {
            alert("Deck name cannot be empty");
            return false;
        } else {
            // Procceed with download to JSON
            var file = {
                "name": "MyDeck",
                "description": "No description provided.",
                "cards": []
            }

            var name = document.getElementById("dName").value;
            if(name != ""){
                file.name = name;
            }
            
            var desc = document.getElementById("dDesc").value;
            if(desc != ""){
                file.description = desc;
            }
            

            $('.yourDeck div img').each(function() {
                var card = this.id;
                file.cards.push(card);
            })

            file = JSON.stringify(file);

            // Download
            var output = document.createElement('a');
            output.href = 'data:attachment/text,' + encodeURI(file);
            output.download = name + ".json";
            output.click();
        }
        
    }

});

