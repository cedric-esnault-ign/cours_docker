pandoc -s -t revealjs -i --section-divs --template=template/ign-revealjs.html --css=css/ign.css -V slideNumber=true docker.md > public/index.html
