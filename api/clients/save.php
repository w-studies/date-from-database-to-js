<?php
  // conecta ao database
  require '../connection.php';
  require '../helpers/httpResponse.php';

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

    $query .= "clients set name = '$name', date ='$date'$where";

    // executa a query
    $sqli->query($query);

    // verifica se houve alguma falha na execução da query
    if ($sqli->error) {
      // devolve json com a mensagem de erro
      jsonResponse('<p class="text-danger"><b>ERROR</b>: ' . $sqli->error . '</p><small class="text-secondary">' . __FILE__ . ' at line: ' . __LINE__ . '<small>', 500);

      // se não houve falha
    } elseif ($sqli->affected_rows) {
      // devolve mensagem de sucesso
      jsonResponse('Registro ' . $action . ' com sucesso, chefe!');
    } else {
      // devolve json com a mensagem de erro
      jsonResponse('Nada foi alterado, chefe!', 400);
    }
  }
