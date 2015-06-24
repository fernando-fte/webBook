<?php
# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #
# Aplicação de comunicação com o servidor dos tipos query   #
# sendo estes quando for "SELECT", "INSERT", UPDATE         #
# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #


# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #
# função de interação com query para o 'sql'
#
function query($sql){
    # # # # #
    # # Descreve valores recebidos de "query($sql){}"
    # $sql = recebe uma string com os valores compilados
    # # # # #

    # adiciona em "$con" a classe responsável pela conexão
    $con = new _conecta;

    # abre conexao
    $con->AbreConexao();

    # seta o valor query
    $Sel = mysql_query($sql) or die(mysql_error());

    # fecha a conexao
    $con->FechaConexao();

    # retorna query
    return $Sel;
}
#
# Fim de  "função de interação com query para o 'sql'"
# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #


# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #
# função de tratamento para valores do tipo 'select'
#
function select($tabela, $campos, $regra){
    # # # # #
    # # Descreve valores recebidos de "select($tabela, $campos, $regra){}"
    # $tabela = 'nome-da-tabela'; // tabela a ser consultada
    # $campos = array('' => '*'); // campos a serem capturados
    # $regra  = array('WHERE ID=' => $id, LIMIT  => '1'); // regras da consulta
    # #
    # solicitação: "select($tabela, $campos, $regra)";
    # # # # #

    # # # # #
    # Tratamento dos valores da função

    # seleciona o valor do nome dos campos da array $campos
    $arrCampo = array_keys($campos);
    
    # seleciona o valor dentro dos campos da array $campos
    $arrValores = array_values($campos);

    # contar a quantidade de campos array possui quanto aos ['campos']
    $numCampo = count($arrCampo);

    # contar a quantidade de campos array possui quanto aos ['dados']
    $numValores = count($arrValores);

    # # #

    # seleciona o valor do nome dos campos da array $regra
    $arrRegraCampo = array_keys($regra);
    
    # seleciona o valor dentro dos campos da array $regra
    $arrRegraValores = array_values($regra);

    # contar a quantidade de campos array possui quanto aos [campos] de $campos
    $numRegraCampo = count($arrRegraCampo);

    # contar a quantidade de campos array possui quanto aos [dados] de $campos
    $numRegraValores = count($arrRegraValores);



    # # # # #
    # Inicia aplicação da função

    # #
    # verifica se os campos repassados são válidos, para o processamento
    if ($numCampo == $numValores && $numRegraCampo == $numRegraValores && $numCampo > '0') {

        # #
        # padrão da seleção: SELECT * FROM edicao WHERE ID='$id'
        # #

        # delimita a ação quando for 'SELECT'
        $sql = 'SELECT '; 

        # trata os campos a serem capturados
        foreach ($arrValores as $valores) {

            # acrecenta em '$sql' os valores a serem resgatados
            $sql .= $valores.', '; 
        }

        # remove possivel espaço em branco do final da contagem anterior
        $sql = substr_replace($sql, ' ', -2, 1);

        # acrecenta em '$sql' a tabela do banco de dados
        $sql .= 'FROM '.$tabela.' ';
   
        # loop para definir as regras de seleção
        for ($i='0'; $i < $numRegraCampo; $i++) {

            # acrecenta em '$sql' o valor das regras do sql
            $sql .= $arrRegraCampo[$i].' '.$arrRegraValores[$i].' ';//regras;
        }

        # seleciona dados do banco, pela função responsável e acrecenta em '$sel'
        $sel = query($sql);

        # loop para repassar os resultados com uma array
        $i = '0'; # inicia em '$i' como um contador

        # laço para processar e atriubir dentro de value os resultados do banco
        while ($val = mysql_fetch_array($sel)) {

            # acrecenta em '$res[0-9*]' os resultados
            $res[$i] = $val;

            # acrecenta (1) no contador
            $i = $i+'1';
        }

        # #
        # valida se houve realmente um acrecimo de array, relacionando se houve um resultado
        if ($res['0']['0'] != '') {

            # conto quantas respostas houve
            $temp['res']['count'] = count($res);

            # confiro se houve mais de uma resposta
            switch($temp['res']['count']){

                # quando tem apenas uma resposta o objeto é colocado no index da array ($res[0][a] -> $res[a])
                case '1':                            
                    return $res = $res['0']; # retorna paneas uma resposta dentro da array
                break;

                # quando tem mais de uma resposta ele exibe normal ($res[0][a]; $res[1][a]; $res[2][a])
                default:
                    return $res; # retorna todas as respostas
                break;
            }
        } 

        # caso não exista nem um resultado
        else {

            # retorna o valor Empty
            return 'Empty'; 
        }

        # Fim de "valida se houve realmente um acrecimo de array, relacionando se houve um resultado"
        # #

    } # "if ($numCampo == $numValores && $numRegraCampo == $numRegraValores && $numCampo > '0')"

    # caso não algun dos argumentos não sejam válidos
    else {
        return '
        Incompatibilidade com um dos campos que exigem arrays, veja a requisição deste objeto.
        <br>
        <a href="?api=functions->sql->select->param">Consulte a api</a>
        ';
    }

    # Fim de "verifica se os campos repassados são válidos, para o processamento"
    # #
}
#
# Fim de "função de tratamento para valores do tipo 'select'"
# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #


# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #
# função de tratamento para valores do tipo 'insert'
#
function insert($tabela, $dados){
    # # # # #
    # # Descreve valores recebidos de "insert($tabela, $dados){}"
    # $tabela = 'nome-da-tabela'; // Tabela a ser consultada
    # $dados  = array('nome-da-coluna' => 'dado'); // dados a serem inseridos
    # #
    # solicitação: "insert($tabela, $dados);";
    # # # # #

    # # # # #
    # Tratamento dos valores da função

    # seleciona o valor do nome dos campos da array $campos
    $arrCampo = array_keys($dados);
    
    # seleciona o valor dentro dos campos da array $campos
    $arrValores = array_values($dados);

    # contar a quantidade de campos array possui quanto aos ['campos']
    $numCampo = count($arrCampo);

    # contar a quantidade de campos array possui quanto aos ['dados']
    $numValores = count($arrValores);



    # # # # #
    # Inicia aplicação da função

    # #
    # verifica se os campos repassados são válidos, para o processamento
    if($numCampo == $numValores && $numCampo > '0'){

        # define que o tipo de seleção de banco será INSERT, e define a tabela a ser acrecentada
        $sql = 'INSERT INTO '.$tabela.' (';

        # laço para acrecentar os campos a serem acrecentados na ordem certa
        foreach ($arrCampo as $campo) {

            # acrecenta em "$sql" os valores dos campos
            $sql .= '`'.$campo.'`, ';
        }

        # acrecenta em "$sql" o final do as regras
        $sql = substr_replace($sql, ') ', -2, 1);

        # # # #    

        # acrecenta em "$sql" o inicio de VALUES, da regra
        $sql .= 'VALUES (';

        # laço para acrecentar para processar os valores a serem inseridos
        foreach ($arrValores as $valores) {

            # acrecenta em "$sql" os valores dos campos
            $sql .= '\''.$valores.'\', ';
        }

        # acrecenta em "$sql" o fim de VALUES, da regra
        $sql = substr_replace($sql, ')', -2, 1);

        # envia os parametros direto a função responsavel pela interação com o banco de dados
        query($sql);

    } # if($numCampo == $numValores && $numCampo > '0')

    # caso não algun dos argumentos não sejam válidos
    else{
        echo '
        Incompatibilidade com um dos campos que exigem arrays, veja a requisição deste objeto.
        <br>
        <a href="?api=functions->sql->insert->param">Consulte a api</a>
        ';
    }
    # Fim de "verifica se os campos repassados são válidos, para o processamento"
    # #
}
#
# Fim de "função de tratamento para valores do tipo 'insert'"
# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #


# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #
# função de tratamento para valores do tipo 'update'
#
function update($tabela, $dados, $regra){
    # # # # #
    # # Descreve valores recebidos de "update($tabela, $dados, $regra){}"
    # $tabela = 'nome-da-tabela'; // tabela a ser consultada
    # $dados  = array('ID' => '3', 'autor' => 'Nome 3 que passa para 4', 'sobre' => 'Sobre o Nome 3 que passa para oi'); // dados a serem alterados conforme a tabela
    # $regra  =  '3'; // ID item a ser atualizado OU
    # $regra  =  array('WHERE ID=' => $id, LIMIT  => '1'); // regra para seleção da linha a ser atualizado
    # #
    # solicitação: "update($tabela, $dados, $regra);";
    # # # # #

    # # # # #
    # Tratamento dos valores da função

    # seleciona o valor do nome dos campos da array $campos
    $arrCampo = array_keys($dados);
    
    # seleciona o valor dentro dos campos da array $campos
    $arrValores = array_values($dados);

    # contar a quantidade de campos array possui quanto aos ['campos']
    $numCampo = count($arrCampo);

    # contar a quantidade de campos array possui quanto aos ['dados']
    $numValores = count($arrValores);



    # # # # #
    # Inicia aplicação da função

    # #
    # verifica se os campos repassados são válidos, para o processamento
    if($numCampo == $numValores && $tabela != '' && $numValores > '0') {

        # define que o tipo de seleção de banco será UPDATE, e define a tabela a ser acrecentada
        $sql = 'UPDATE '.$tabela.' SET ';

        # laço para atribuir as regras relacionada aos valores
        for ($i='0'; $i < $numCampo ; $i++) { 

            # acrecenta em "$sql" as regras e valores
            $sql .= '`'.$arrCampo[$i].'` = \''.addslashes($arrValores[$i]).'\', ';
        }

        # remove os ultimos espaços do parametro dentro de "$sql"
        $sql = substr_replace($sql, '', '-2', '1');

        # #
        # valida quando a regra seja uma array com valores variados
        if (is_array($regra)) {

            # seleciona o valor do nome dos campos da array $regra
            $arrRegraCampo = array_keys($regra);
            
            # seleciona o valor dentro dos campos da array $regra
            $arrRegraValores = array_values($regra);

            # contar a quantidade de campos array possui quanto aos [campos] de $campos
            $numRegraCampo = count($arrRegraCampo);

            # contar a quantidade de campos array possui quanto aos [dados] de $campos
            $numRegraValores = count($arrRegraValores);

            # laço para configurar os campos e as regras
            for ($i='0'; $i < $numRegraCampo; $i++) {

                # acrecenta em "$sql" os campos e valores
                $sql .= $arrRegraCampo[$i].' '.$arrRegraValores[$i].' ';
            }

        } # if (is_array($regra)) {

        # caso a regra seja uma string com o valor de id
        else {

            # acrecenta em "$sql" que a seleção será em id e o valor para este
            $sql .= 'WHERE Id='.$id;
        }
        # Fim de "valida quando a regra seja uma array com valores variados"
        # #

        # envia os parametros direto a função responsavel pela interação com o banco de dados
        query($sql); 

    } # if($numCampo == $numValores && $tabela != '' && $numValores > '0') 

    # caso não algun dos argumentos não sejam válidos
    else {
        return $return['success'] = 'Incompatibilidade com um dos campos que exigem arrays, veja a requisição deste objeto.';
    }
    # Fim de "verifica se os campos repassados são válidos, para o processamento"
    # #
}
#
# Fim de "função de tratamento para valores do tipo 'update'"
# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # #

?>