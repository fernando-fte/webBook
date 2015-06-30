#função para importar documento xml
load_xml = () ->
  $.ajax
    url: "source.xml"
    dataType: "xml"
    async:false
    beforeSend: ->
      console.log "Enviando"

    success: (xml) ->
      return xml

# chama documento xml
xml = load_xml ""


# console.log a.responseText
temp = {}

xmlDOM = jQuery.parseXML(xml.responseText)

# console.log $($(xmlDOM).find('author-item')[0]).find('[type=nome]').text()

# # # #
# CRIANDO MANIPULAÇÃO

# cria html
htmlDocument = '
  <div class="container">
    <h1 class="title"/>
    <div class="autor-group">
      <div class="autor-item">
        <h2 class="autor-item autor-nome"></h2>
        <p class="autor-item autor-titulacao"></p>
        <p class="autor-item autor-apresentacao"></p>
      </div>
    </div>
  </div>
'

# adiciona declaração no documento
$('body').prepend htmlDocument

#ADICIONA CONTEUDOS
# adiciona titulo no header
$('title').text($(xmlDOM).find('titulo').text())

# adiciona titulo no corpo
$('body h1.title').text($(xmlDOM).find('titulo').text())

# adiciona autor
temp.count = 0
while $(xmlDOM).find('author-item').size() > temp.count

  if $('.autor-group div.autor-item').size() <= ($(xmlDOM).find('author-item').size()-1)
    $($('.autor-group div.autor-item')[0]).clone().prependTo('.autor-group')

    $('.autor-group div.autor-item .autor-nome').prepend($($(xmlDOM).find('author-item')[0]).find('[type=nome]').text())
    $('.autor-group div.autor-item .autor-titulacao').prepend($($(xmlDOM).find('author-item')[0]).find('[type=titulacao]').text())
    $('.autor-group div.autor-item .autor-apresentacao').prepend($($(xmlDOM).find('author-item')[0]).find('[type=apresentacao]').html())

  ++temp.count
