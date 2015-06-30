temp = {}

# adiciona estrutura XML
xml = '
<?xml version="1.0" encoding="UTF-8"?>
<book>
    <header>
      <titulo>Finanças Empresariais</titulo>
      <author-group>
          <author-item>
              <info type="nome">Marcela Ribeiro de Albuquerque</info>
              <info type="titulacao">Mestre em Economia - UEM</info>
              <info type="apresentacao"><p>Meu nome é Marcela, sou bacharel em Ciências Econômicas pela Universidade Estadual de Maringá e Mestre em Economia pela Universidade Estadual de Maringá. Atuo na docência desde 2007, ensino presencial e a distância, ministrando diversas disciplinas na área de economia em vários cursos de graduação de instituições de ensino superior públicas e privadas. Também atuo no ensino a distância junto a Universidade Aberta do Brasil - Capes e em cursos de Pós-Graduação Lato Sensu. Em 2012 passei a integrar a carreira do magistério em ensino superior no Estado do Paraná, sendo servidora pública da Universidade Estadual do Norte do Paraná - Campus de Cornélio Procópio. Em função da impossibilidade em ficar afastada de minhas atividades docentes para o processo de capacitação, infelizmente não pude concluir o Doutorado em Economia na Universidade Federal de Santa Catarina. Minha área de pesquisa se concentra nos estudos das políticas sociais de combate à pobreza e nas questões relacionadas às desigualdades sociais e concentração da riqueza.</p></info>
          </author-item>
          <author-item>
              <info type="nome">Marcela Ribeiro de Albuquerque</info>
              <info type="titulacao">Mestre em Economia - UEM</info>
              <info type="apresentacao"><p>Meu nome é Marcela, sou bacharel em Ciências Econômicas pela Universidade Estadual de Maringá e Mestre em Economia pela Universidade Estadual de Maringá. Atuo na docência desde 2007, ensino presencial e a distância, ministrando diversas disciplinas na área de economia em vários cursos de graduação de instituições de ensino superior públicas e privadas. Também atuo no ensino a distância junto a Universidade Aberta do Brasil - Capes e em cursos de Pós-Graduação Lato Sensu. Em 2012 passei a integrar a carreira do magistério em ensino superior no Estado do Paraná, sendo servidora pública da Universidade Estadual do Norte do Paraná - Campus de Cornélio Procópio. Em função da impossibilidade em ficar afastada de minhas atividades docentes para o processo de capacitação, infelizmente não pude concluir o Doutorado em Economia na Universidade Federal de Santa Catarina. Minha área de pesquisa se concentra nos estudos das políticas sociais de combate à pobreza e nas questões relacionadas às desigualdades sociais e concentração da riqueza.</p></info>
          </author-item>
      </author-group>
    </header>
</book>
'
xmlDOM = jQuery.parseXML(xml)

# console.log $($(xmlDOM).find('author-item')[0]).find('[type=nome]').text()

# # # #
# CRIANDO MANIPULAÇÃO

# cria html
htmlDocument = '
  <div class="container">
    <h1 class="title"/>
    <div class="author-group">
      <div class="autor-item">
        <h2 class="autor-item autor-nome"></h2>
        <p class="autor-item autor-titulacao"></p>
        <p class="autor-item autor-apresentação"></p>
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
console.log $(xmlDOM).find('author-item').size()

temp.count = 0
while $(xmlDOM).find('author-item').size() > temp.count

  $('.author-item').clone().prependTo('.author-group')

  $(xmlDOM).find('author-item')[temp.count]

  ++temp.count


# autor.count = 0
# while autor.size() > autor.count
#   $(autor[autor.count]).find('info [type="name"]')
#   ++ autor.count
