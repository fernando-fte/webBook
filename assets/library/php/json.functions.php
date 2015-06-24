<?php  
# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #
# FUNÇÕES DE APOIO PARA O TRATAMENTO DE JSON                #
# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #

# # # # #
# função para transformar [object Object] json em array
function f_object_to_array($object) {

    # verifica se $object não é um [object Object] e nem uma array
    if(!is_object($object) && !is_array($object)) { 

        # devolve valor a função sem modificações
        return $object; 
    }

    # valida se a $object é um [object Object]
    if(is_object($object)) {

        # adiciono em $object ele mesmo processado com a função get_object_vars()
        $object = get_object_vars( $object);
    }
   
   # retorna uma array pameada de $object com 'objectToArray'
   return array_map( 'objectToArray', $object );
}
# função para transformar [object Object] json em array
# # # # #


# # # # #
# função para montar os parametros para um select
function f_json_where($post) {

    # verifica se o valor recebido em $post possui a array "select"
    if (array_key_exists("select", $post)) {

        # # # 
        # separa arrays recebidas de $post

        # adiciona em $temp>key uma lista com os valores das chaves de $post>select
        $temp['key'] = array_keys($post['select']);

        # adiciona em $temp>val uma lista com os valores de cada array em $post>select
        $temp['val'] = array_values($post['select']);

        # adiciona em $temp>key>count quantas arrays foram adicionadas em $temp>key
        $temp['key']['count'] = count($temp['key']);

        # adiciona em $temp>val>count quantas arrays foram adicionadas em $temp>val
        $temp['val']['count'] = count($temp['val']);

        # separa arrays recebidas de $post
        # # # 


        # # # 
        # trata valores compilados, validando e montando regra na seção WHERE conforme o indicador

        # selecina em loop cada array de $temp>val
        for ($temp['count']=0; $temp['count'] < $temp['val']['count']; $temp['count']++) {

            # #
            # quando $post>regra>where tiver o tipo de seleção WHERE especifico
            if ($post['regra']['where'] == "LIKE" or $post['regra']['where'] == "=") {

                # configura a primeira entrada do loop, que pode ser a unica, selecionando apenas uma coluna
                if($temp['count'] <= 0) {

                    # monta em $temp>where a chamda da regra de validação de select()
                    $temp['where'] = '`'.$temp['key'][$temp['count']].'` '.$post['regra']['where'].' \''.$temp['val'][$temp['count']].'\' ';
                }

                # configura a partir da segunda entrada do loop, para selecionar mais de uma coluna
                else{

                    # adiciona em $temp>where ele mesmo mais a concatenação "AND" e a segunda leva da regra de seleção
                    $temp['where'] = $temp['where'] .'AND `'.$temp['key'][$temp['count']].'` '.$post['regra']['where'].' \''.$temp['val'][$temp['count']].'\' ';
                }
            }

            # #
            # quando $post>regra>where tiver o tipo de seleção WHERE relativo
            else if ($post['regra']['where'] == "LIKE%") {

                # configura a primeira entrada do loop, que pode ser a unica, selecionando apenas uma coluna
                if($temp['count'] <= 0) {

                    # monta em $temp>where a chamda da regra de validação de select()
                    $temp['where'] = '`'.$temp['key'][$temp['count']].'` LIKE \'%'.$temp['val'][$temp['count']].'%\' ';
                }

                # configura a partir da segunda entrada do loop, para selecionar mais de uma coluna
                else{

                    # adiciona em $temp>where ele mesmo mais a concatenação "AND" e a segunda leva da regra de seleção
                    $temp['where'] = $temp['where'] .'AND `'.$temp['key'][$temp['count']].'` LIKE \'%'.$temp['val'][$temp['count']].'%\' ';
                }
            }
        }

        # trata valores compilados, validando e montando regra na seção WHERE conforme o indicador
        # # # 


        # # # 
        # finaliza montagem da das regras dentro das arrays de retorno e adiciona em $regra, lembrando que a função leva em conta a array enviada

        # acrecenta em $regra>'WHERE ' os valores manipulados e tratados acrecentados em $temp>where
        $regra['WHERE '] =  $temp['where'];

        # acrecenta em $regra>'WORDER BY ' os dados de ordenação de resultados, recebidos em $post>regra>order
        $regra['ORDER BY '] =  '`'.$post['regra']['order']['to'].'` '.$post['regra']['order']['by'].'';


        # #
        # adiciona 'LIMIT' caso o valor de $post>regra>limit seja valido ou maior que 0
        if ($post['regra']['limit'] != false && $post['regra']['limit'] > 0) {

            # acrecenta em $regra>'LIMIT ' a quantidade de dados a serem retornados, recebido em $post>regra>limit
            $regra['LIMIT '] =  $post['regra']['limit'];
        }

        # apaga $temp na posição atua
        unset($temp);

        # retorna regra para a função
        return $regra;
        
        # finaliza montagem da das regras dentro das arrays de retorno e adiciona em $regra, lembrando que a função leva em conta a array enviada
        # # # 
    }
}
# função para montar os parametros para um select
# # # # #


# # # # #
# função para retornar apenas o campo "values" recebido do banco de dados
function f_json_values($select){

    # # # 
    # caso exista a array "values" na raiz de $select, sendo apenas um retorno do servidor
    if (array_key_exists("values", $select)) {

        # acrecenta na string $return os valores de $select>values
        $return = $select['values'];
    }

    # # # 
    # caso não exista a array "values" na raiz de $select, podendo ser mais de um valor retornado
    else{

        # conta quantas ocorrencias
        $temp['count'] = count($select);

        # excecuta loop para tratar cada resposta do servidor
        for ($temp['position'] = 0; $temp['position'] < $temp['count']; $temp['position']++) {

            # quando for o primeiro o primeiro resultado
            if($temp['position'] <= 0) {

                # adiciona em $temp>return os "values" da posição em $select>%%>values
               $temp['return'] = $select[$temp['position']]['values'];
            }

            # quando do segundo resultado em diante
            else{

                # acrecenta em $temp>return ele mesmo mais os "values" da posição em $select>%%>values
                $temp['return'] = $temp['return'] .", ". $select[$temp['position']]['values'];
            }

            # acrecenta em $return os valores tratados em $temp>return
            $return = $temp['return'];
        }
    }

    # retorna os valores para a função
    return $return;
}
# função para retornar apenas o campo "values" recebido do banco de dados
# # # # #


# # # # #
# função para validar o status do values>[object Object]
function f_valida_status ($return) {

    # acrecenta em $return ele mesmo como array
    $return = (json_decode($return, true));

    # valida se na raiz existe "status"
    if(array_key_exists('status', $return['0'])){

        # acrecenta m $return>0>status ele mesmo com a font em smallcase
        $return['0']['status'] = strtolower($return['0']['status']);

        # inicia sequencia de selecao de status
        switch ($return['0']['status']) {

            # caso seja ativo retorna 'true'
            case 'active':
                return true;
                break;
            case 'actived':
                return true;
                break;
            case 'ativo':
                return true;
                break;
            case 'desativo':
                return true;
                break;

            # caso seja desativado retorna 'false'
            case 'dasebled':
                return false;
                break;
            case 'desable':
                return false;
                break;
            case 'inative':
                return false;
                break;
            case 'inativo':
                return false;
                break;
            case 'desativo':
                return false;
                break;

            # caso seja exluido retorna 'excluded'
            case 'exclude':
                return 'excluded';
                break;
            case 'removed':
                return 'excluded';
                break;
            case 'excluido':
                return 'excluded';
                break;
        }
    }

    # quando não houver o atributo status nao retorna 'true'
    else{
        return true;
    }
}
# função para validar o status do values>[object Object]
# # # # #


# # # # #
# função para validar se as entradas do $_POST são corretas
function f_json_post($post) {

    # #
    # quando não existir a array "regra", adiciona as configurações necessárias
    if (!array_key_exists("regra", $post)) {

        # adiciona na array $post>regra>where o valor que a busca é fixa (LIKE | LIKE%)
        $post['regra']['where'] = "LIKE";
        
        # adiciona na array $post>regra>limit que a resposta do servidor será apenas '1'
        $post['regra']['limit'] = "1";

        # adiciona na array $post>order>to que a busca sera ordenada em "index"
        $post['regra']['order']['to'] = "index";

        # adiciona na array $post>order>by que a busca será ordenada do menor para o maior
        $post['regra']['order']['by'] = "ASC";
    }
    # Fim da 'quando não existir a array "regra", adiciona as configurações necessárias'
    # #

    # #
    # trata elementos caso exista a array "regra", valida cada item dentro da mesma 
    else {
        # #
        # valida "where" que estabelece a seleção
        if(!array_key_exists("where", $post['regra'])) {

            # adiciona na array $post>regra>where o valor que a busca é fixa (LIKE | LIKE%)
            $post['regra']['where'] = "LIKE";
        }
        # Fim de 'valida "where" que estabelece a seleção'
        # #

        # #
        # valida se há "order" que estabelece a ordem das respostas (que a coluna "X" exibida de 0 -> ∞ ou ∞ -> 0)
        if(!array_key_exists("order", $post['regra'])) {

            # adiciona na array $post>order>to que a busca sera ordenada em "index"
            $post['regra']['order']['to'] = "index";

            # adiciona na array $post>order>by que a busca será ordenada do menor para o maior
            $post['regra']['order']['by'] = "ASC";
        }
        # Fim de 'valida se há "order" que estabelece a ordem das respostas (que a coluna "X" exibida de 0 -> ∞ ou ∞ -> 0)'
        # #

        # #
        # valida se os valores de "order" estão corretos
        else { 

            # #
            # valida se há "order>to"
            if(!array_key_exists("to", $post['regra']['order'])) {

                # adiciona na array $post>order>to que a busca sera ordenada em "index"
                $post['regra']['order']['to'] = "index";
            }
            # Fim de 'valida se há "order>to"'
            # #

            # #
            # valida se há "order>by"
            if(!array_key_exists("by", $post['regra']['order'])) {

                # adiciona na array $post>order>by que a busca será ordenada do menor para o maior
                $post['regra']['order']['by'] = "ASC";
            }
            # Fim de 'valida se há "order>by"'
            # #
        }
        # Fim de "valida se os valores de "order" estão corretos"
        # #

        # #
        # valida se há "limit", e este define quantos resultados o banco deve retornar
        if(!array_key_exists("limit", $post['regra'])) {

            # adiciona na array $post>regra>limit que a resposta do servidor será apenas '1'
            $post['regra']['limit'] = "1";
        }
        # valida se há "limit", e este define quantos resultados o banco deve retornar
        # #
    }
    # Fim de 'trata elementos caso exista a array "regra", valida cada item dentro da mesma '
    # #

    # #
    # valida a array "status" existe
    if(!array_key_exists("status", $post)){

        # determina que "status" é falso
        $post['status'] = false;
    }
    # Fim de 'valida a array "status"'
    # #

    # retorna $post para a função
    return $post;
}
# função para validar se as entradas do $_POST são corretas
# # # # #



# # # # #
# função para criar history
function f_new_history($post) {

    # #
    # define e seleciona valores do banco de dados

    # adiciona em $temp>regra os valores de $post na função 'f_json_where'
    $temp['new-update']['regra'] = f_json_where($post);

    # adiciona em $temp>tabela o valor referente a tabela com tratamento especifico
    $temp['new-update']['tabela'] = '`'.$post['table'].'`'; 

    # adiciona em $temp>campos os campos a serem selecionado, no caso "*" todos por padrão
    $temp['new-update']['campos'] = array('' => '*');

    # seleciona os valores do banco de dados recebidos da função select() com os dados tratados anteriormente
    $temp['new-update']['select'] = select($temp['new-update']['tabela'], $temp['new-update']['campos'], $temp['new-update']['regra']);

    # Fim de "define e seleciona valores do banco de dados"
    # #


    # mapeia as respostas do servidor como object json para array
    $temp['new-update']['select']['values'] = json_decode($temp['new-update']['select']['values'], true);


    # # #
    # caso não exista $temp>new-update>select>values>htmlGetSQL.setings configura suas propriedades e envia ao banco
    if(!array_key_exists('htmlGetSQL.setings', $temp['new-update']['select']['values'])) {

        # configura htmlGetSQL>setings>selectors para table
        $temp['new-update']['select']['values']['htmlGetSQL.setings']['htmlGetSQL.selectors']['table'] = $post['table'];

        # configura htmlGetSQL>setings>selectors para select
        $temp['new-update']['select']['values']['htmlGetSQL.setings']['htmlGetSQL.selectors']['select']['index'] = $temp['new-update']['select']['index'];
        $temp['new-update']['select']['values']['htmlGetSQL.setings']['htmlGetSQL.selectors']['select']['segmento'] = $temp['new-update']['select']['segmento'];
        $temp['new-update']['select']['values']['htmlGetSQL.setings']['htmlGetSQL.selectors']['select']['grupo'] = $temp['new-update']['select']['grupo'];
        $temp['new-update']['select']['values']['htmlGetSQL.setings']['htmlGetSQL.selectors']['select']['type'] = $temp['new-update']['select']['type'];
        $temp['new-update']['select']['values']['htmlGetSQL.setings']['htmlGetSQL.selectors']['select']['sku'] = $temp['new-update']['select']['sku'];

        # mapeia os dados de resposta do servidor e transforma em object json
        $temp['new-update']['dados']['values'] = json_encode($temp['new-update']['select']['values']);

        # envia atualização para o original
        update($temp['new-update']['tabela'], $temp['new-update']['dados'], $temp['new-update']['regra']);

        # zera valor de dados
        unset($temp['new-update']['dados']);
    }


    # # # #
    # Inicia novo history para armazenar as alterações

    # acrecenta em $temp>new-update>history>montagem>date a string '0000-00-00 00:00:00'
    $temp['new-update']['history']['montagem']['date'] = date('Y-m-d').' '.date('h:i:s');

    # acrecenta em $temp>new-update>history>montagem>md5 o valor do md5 dade + sku do select + o sku do registro
    $temp['new-update']['history']['montagem']['md5'] = md5(
         $temp['new-update']['history']['montagem']['date']
        .$temp['new-update']['select']['values']['htmlGetSQL.setings']['htmlGetSQL.selectors']['select']['sku']
        .$post['log']['registro']['reg']
    );

    # acrecenta em $temp>new-update>history>montagem>sku o valor dos 5 primeiros digitos + os 5 ultimos de $temp>new-update>history>montagem>md5
    $temp['new-update']['history']['montagem']['sku'] = substr($temp['new-update']['history']['montagem']['md5'], 0, 5) . substr($temp['new-update']['history']['montagem']['md5'], -5);


    # seleciona history para validar o campo 
    function valida_history($sku, $type) {

        $temp['regra']['where'] = 'LIKE';
        $temp['regra']['limit'] = '1';
        $temp['regra']['order']['to'] = 'history';
        $temp['regra']['order']['by'] = 'ASC';

        $temp['select']['history'] = $sku;

        # adiciona em $temp>regra os valores de $post na função 'f_json_where'
        $temp['sku']['regra'] = f_json_where($temp);

        # adiciona em $temp>tabela o valor referente a tabela com tratamento especifico
        $temp['sku']['tabela'] = '`htmlgetsql.history`'; 

        # adiciona em $temp>campos os campos a serem selecionado, no caso "*" todos por padrão
        $temp['sku']['campos'] = array('' => 'history');

        # seleciona os valores do banco de dados recebidos da função select() com os dados tratados anteriormente
        $temp['sku']['select'] = select($temp['sku']['tabela'], $temp['sku']['campos'], $temp['sku']['regra']);


        # caso a seleção seja do tipo retorno

        if (!$type) {

            # caso não exista nem uma ocorrencia deste valor
            if ($temp['sku']['select'] == 'Empty') {

                return $sku;
            }

            # caso exista algum huistory com este valor
            else {

                # adiciona a data atual em md5 + sku atual
                $sku = md5($sku.date('Y-m-d').' '.date('h:i:s'));

                # subtrai os 5 ultimos e 5 primeiros digitos
                $sku = substr($sku, 0, 5) . substr($sku, -5);

                # repasso para a função revalidando novamente
                return valida_history($sku, false);
            }
        }

        elseif ($type) {

            # caso não exista nem uma ocorrencia deste valor
            if ($temp['sku']['select'] != 'Empty') {

                # retorna verdadeiro
                return true;
            }

            # caso exista algum huistory com este valor
            else {

                # retorna falso
                return false;
            }
        }
    }


    # valida se sku não é duplicado
    $temp['new-update']['history']['montagem']['sku'] = valida_history($temp['new-update']['history']['montagem']['sku'], false);


    # # #
    # # monta valores para insert

    # acrecenta em $temp>new-update>history>campos>history o valor sku para history em $temp>new-update>history>montagem>sku
    $temp['new-update']['history']['campos']['history'] =  $temp['new-update']['history']['montagem']['sku'];

    # acrecenta em $temp>new-update>history>campos>sku o valor sku do select trabalhados em $temp>new-update>select>values>htmlGetSQL.setings>htmlGetSQL.selectors>select>sku
    $temp['new-update']['history']['campos']['sku'] =  $temp['new-update']['select']['values']['htmlGetSQL.setings']['htmlGetSQL.selectors']['select']['sku'];

    # acrecenta em $temp>new-update>history>campos>table o valor table do select em $temp>new-update>select>values>htmlGetSQL.setings>htmlGetSQL.selectors>table
    $temp['new-update']['history']['campos']['table'] =  $temp['new-update']['select']['values']['htmlGetSQL.setings']['htmlGetSQL.selectors']['table'];

    # acrecenta em $temp>new-update>history>campos>log o valor sku do reg do usuario em $postlog>registro>reg
    $temp['new-update']['history']['campos']['log'] =  $post['log']['registro']['reg'];


    # # # #
    # # # Configura $temp>new-update>history>campos>values

    # # # # # 
    # # # # configura valor atual

    # # # adiciona data
    $temp['new-update']['history']['campos']['values']['history']['atual']['date']['create'] = $temp['new-update']['history']['montagem']['date'];
    # # # # adiciona os values
    $temp['new-update']['history']['campos']['values']['history']['atual']['values'] = $post['values'];

    # # # # configura valor atual
    # # # # # 


    # # # # # 
    # # # # configura backup do original

    # # # adiciona data
    $temp['new-update']['history']['campos']['values']['history']['backup']['original']['date']['create'] = $temp['new-update']['history']['montagem']['date'];
    # # # # adiciona os values
    $temp['new-update']['history']['campos']['values']['history']['backup']['original']['values'] = $temp['new-update']['select']['values'];

    # # # # configura backup do original
    # # # # # 

    
    # # # # #
    # # # # Configura setings

    # # # # configura htmlGetSQL>setings>selectors para tabela
    $temp['new-update']['history']['campos']['values']['htmlGetSQL.setings']['htmlGetSQL.selectors']['table'] = 'htmlgetsql.history';

    # # # # configura htmlGetSQL>setings>selectors para select
    $temp['new-update']['history']['campos']['values']['htmlGetSQL.setings']['htmlGetSQL.selectors']['select']['history'] = $temp['new-update']['history']['campos']['history'];
    $temp['new-update']['history']['campos']['values']['htmlGetSQL.setings']['htmlGetSQL.selectors']['select']['sku'] = $temp['new-update']['history']['campos']['sku'];
    $temp['new-update']['history']['campos']['values']['htmlGetSQL.setings']['htmlGetSQL.selectors']['select']['table'] = $temp['new-update']['history']['campos']['table'];
    $temp['new-update']['history']['campos']['values']['htmlGetSQL.setings']['htmlGetSQL.selectors']['select']['log'] = $temp['new-update']['history']['campos']['log'];

    # # # # Configura setings
    # # # # #


    # # # # mapeia os dados de resposta do servidor e transforma em object json
    $temp['new-update']['history']['campos']['values'] = json_encode($temp['new-update']['history']['campos']['values'], true);

    # # # Configura $temp>new-update>history>campos>values
    # # # #

    # # monta valores para insert
    # # #

    # incherta os valores tratados no banco de dados pela função insert()
    insert('`htmlgetsql.history`', $temp['new-update']['history']['campos']);

    # Inicia novo history para armazenar as alterações
    # # # #

    # # #
    # retorna para o js o resultado

    # retorna tipo do trabalho
    $temp['new-update']['return']['type'] = 'new-history';

    # caso o history tenha sido criado
    if (valida_history($temp['new-update']['history']['montagem']['sku'], true)){

        # adiciona o valor do history para $temp>new-update>return>history
        $temp['new-update']['return']['history'] = $temp['new-update']['history']['montagem']['sku'];

        # adiciona o valor do sku do valor trabalhado
        $temp['new-update']['return']['connect']['sku'] = $temp['new-update']['select']['values']['htmlGetSQL.setings']['htmlGetSQL.selectors']['select']['sku'];

        # adiciona o valor da tabela do valor trabalhado
        $temp['new-update']['return']['connect']['table'] = $temp['new-update']['select']['values']['htmlGetSQL.setings']['htmlGetSQL.selectors']['table'];

        # retorna status do trabalho com verdadeiro
        $temp['new-update']['return']['success'] = true;

        # retorna o valor para js
        return $temp['new-update']['return'];
    }

    # caso o history não tenha sido criado
    else {

        # retorna status do trabalho com falso
        $temp['new-update']['return']['success'] = false;

        # retorna o valor para js
        return $temp['new-update']['return'];
    }
}
# função para criar history
# # # # #



# # # # #
# função merger recursive com retorno de array
function f_merger($temp) {
    # recebe em temp o conjunto array
    # ['content'] = Conteúdo original
    # ['replace'] = Conteúdo direcional
    # ['setings']:(return|replace) = metodo de tratamento
    # ['play']:bolean = regra de verificação das regras e parametros função

    # verifica se não existe play, caso tenha os atributos foram validados
    if (!array_key_exists('start', $temp)) {

        # define que temp>play é falso
        $temp['start'] = false;

        # verifica se existe os valores de replace
        if (array_key_exists('setings', $temp)) {

            # verifica se existe os valores de replace
            if (array_key_exists('replace', $temp)) {

                # verifica se existe os valores de contents
                if (array_key_exists('content', $temp)) {

                    # define que temp start pode ser verdadeito
                    $temp['start'] = true;
                }
            }
        }
    }

    # monta caso start true
    if ($temp['start']) {

        # executa loop para selecionar cada item
        foreach ($temp['replace'] as $k => $v) {

            # verifica se os values de temp>replace tem sub conteúdos
            if(is_array($v) && is_array($temp['content'][$k])){

                # monta os valores temporários para montagem do dados array
                $temp['send']['replace'] = $temp['replace'][$k];
                $temp['send']['content'] = $temp['content'][$k];
                $temp['send']['setings'] = $temp['setings'];
                $temp['send']['start']    = true;

                # adiciona em return o valor da função
                $temp['return'] = f_merger($temp['send']);

                # acrecenta em temp>replace[k] o valor de replace retornado
                $temp['replace'][$k] = $temp['return']['replace'];

                # acrecenta em temp>content[k] o valor de replace retornado
                $temp['content'][$k] = $temp['return']['content'];
            }

            # caso temp>replace não seja uma estrutura array
            else{

                # caso setings seja para replace, o final de replace deve subistituir contents
                if($temp['setings'] == 'replace'){

                    # replace subistitui contents
                    $temp['content'][$k] = $temp['replace'][$k];
                }

                # caso setings seja para replace, o final de replace deve subistituir contents
                if($temp['setings'] == 'return'){

                    if(is_array($temp['content'])) {

                        # replace subistitui contents, quando array
                        $temp['replace'][$k] = $temp['content'][$k];
                    }

                    else {

                        # replace subistitui content
                        $temp['replace'] = $temp['content'];
                    }
                }
            }
        }

        # monta os valores temporários para montagem do dados array
        $return['replace'] = $temp['replace'];
        $return['content'] = $temp['content'];
        $return['setings'] = $temp['setings'];

        unset($temp);

        return $return;
    }

    # retorna erro caso os parametros estejam errados
    else {

        return 'Erro na sintax da solicitação ($temp = {content | replace | setings})';
    }
}
# função merger recursive com retorno de array
# # # # #

?>
