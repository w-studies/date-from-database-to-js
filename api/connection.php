<?php

  require 'helpers/httpResponse.php';

// definição de variáveis para conexão
  $host     = 'localhost';      // servidor
  $user     = 'root';           // nome do utilizador
  $pass     = 'my-secret-pw';   // senha ou password
  $database = 'date_studies';   // nome da base de dados

// estabelecer conexão:
  $sqli = new mysqli($host, $user, $pass);

// verificando se conectou de boas:
  if ($sqli->connect_error) {
    // se houver alguma falha, exibe mensagem:
    jsonResponse('<p class="text-danger">Falha na conexão: ' . $sqli->connect_error . '</p>', 400);
  }

// definir o padrão de caracteres
  if (!$sqli->set_charset('utf8')) {
    // se não conseguir definir o padrão de caracteres, exibe o padrão disponível
    jsonResponse("<p class='text-danger'>Seu charset não é utf8, chefe!<br>$sqli->character_set_name()</p>", 400);
  }

// selecionar/abrir o banco de dados para trabalhar
  if (!$sqli->select_db($database)) {
    // se o banco de dados não for encontrado
    jsonResponse("<p class='text-danger'>Banco de dados não encontrado, chefe!</p>", 400);
  }
