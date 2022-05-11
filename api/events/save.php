<?php
  // conecta ao database
  require '../connection.php';

  // se algo foi postado
  if (sizeof($_POST)) {
    // extrai as variáveis em $_POST
    extract($_POST);
    $event = $sqli->real_escape_string($event);
    $date  = $sqli->real_escape_string($date);
    $id = (int) $id;


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
      jsonResponse('ERROR: ' . $sqli->error, 404);

      // se não houve falha
    } elseif ($sqli->affected_rows) {
      // devolve mensagem de sucesso
      jsonResponse('Registro ' . $action . ' com sucesso, chefe!');
    } else {
      // devolve json com a mensagem de erro
      jsonResponse('Nada foi alterado, chefe!', 400);
    }
  }
