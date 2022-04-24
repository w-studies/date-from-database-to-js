<?php
  // conecta ao database
  require 'connection.php';

  // define a query de busca
  $query = 'select * from schedules';

  if (isset($_GET['id'])) {
    $id    = (int)$_GET['id'];
    $query .= ' where id = ' . $id;
  }

  $query .= ' order by date desc';

  // executa a query de busca e guarda o resultado em $result
  $result = $sqli->query($query);

  // verifica se houve alguma falha na execução da query
  if ($sqli->error) {
    // se houve alguma falha, exibe mensagem:
    die('<p class="error">Falha na conexão: ' . $sqli->error . '</p>' . __FILE__ . ' at line: ' . __LINE__);

    // se não houve falha e algum registro foi encontrado
  } elseif ($result->num_rows) {
    // guarda os registros em $dada
    $data = $result->fetch_all(MYSQLI_ASSOC);

    // encerra o script devolvendo um json
    die(json_encode($data));

    // se não houve erro, e nenhum registro foi encontrado
  } else {
    // devolve json com mensagem
    die(json_encode([
      'error' => 'Nenhum registro foi encontrado.'
    ]));

  }
