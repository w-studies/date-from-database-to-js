<?php
  // conecta ao database
  require 'connection.php';

  // se algo foi postado
  if (sizeof($_POST)) {
    // extrai as variáveis em $_POST
    extract($_POST);


    // monta a query de inserção/update
    $query  = 'insert into ';
    $where  = '';
    $action = 'inserido';

    if (isset($id)) {
      $query  = 'update ';
      $where  = ' where id = ' . (int)$id;
      $action = 'atualizado';
    }

    $query .= "schedules set event = '$event', date ='$date'$where";

    // executa a query
    $sqli->query($query);

    // verifica se houve alguma falha na execução da query
    if ($sqli->error) {
      // devolve json com a mensagem de erro
      die(json_encode([
        'error' => 'ERROR: ' . $sqli->error
      ]));

      // se não houve falha
    } else {
      // devolve mensagem de sucesso
      die(json_encode([
        'success' => 'Registro ' . $action . ' com sucesso, chefe!'
      ]));
    }
  }
