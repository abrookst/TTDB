$(document).ready(()=> {

    document.getElementById('import').onclick = function () {
        var files = document.getElementById('file').files;
        console.log(files);
        if (files.length <= 0) {
            alert("Empty File!");
            return false;
        }

        var fr = new FileReader();

        fr.onload = function (e) {
            $("#allCards").html("");
            generateCards();
            var result = JSON.parse(e.target.result);
            var name = result.name;
            var desc = result.description;
            const illegal = ["'\'", "/", ":", "*", '"', "<", ">", "|", ","];
            for (var i = 0; i < illegal.length; ++i) {
                if (name.includes(illegal[i])) {
                    alert('Deck cannot have any of these characters: \\ / : * " < > | ,');
                    return false;
                }
            }
            document.getElementById('dName').value = name;
            document.getElementById('dDesc').value = desc;
            let cards = result.cards;
            if(cards.length < 15){
                alert("Improperly Formatted JSON!");
                return false;
            }
            for (var i = 0; i <= 15; i++) {
                let id = cards[i];
                document.getElementById(id).remove();
                output = '<img src="../resources/images/cards/'+id+'.webp" id="'+id+'" class="card" draggable="true" ondragstart="drag(event)"></img>';
                let curDiv = '#div' + (i+1);
                $(curDiv).html(output);
            }
        }

        fr.readAsText(files.item(0));
        document.getElementById("file").value = null;
    };
});
