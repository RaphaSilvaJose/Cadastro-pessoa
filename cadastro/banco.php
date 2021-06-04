
<?php
 
 // Verificar se foi enviando dados via POST
 if ($_SERVER['REQUEST_METHOD'] == 'POST') {
     $id = (isset($_POST["id"]) && $_POST["id"] != null) ? $_POST["id"] : "";
     $nome = (isset($_POST["nome"]) && $_POST["nome"] != null) ? $_POST["nome"] : NULL;
     $idade = (isset($_POST["idade"]) && $_POST["idade"] != null) ? $_POST["idade"] : NULL;
     $turma = (isset($_POST["turma"]) && $_POST["turma"] != null) ? $_POST["turma"] : "";
     $nota1 = (isset($_POST["nota1"]) && $_POST["nota1"] != null) ? $_POST["nota1"] : "";
     $nota2 = (isset($_POST["nota2"]) && $_POST["nota2"] != null) ? $_POST["nota2"] : "";
     $nota3 = (isset($_POST["nota3"]) && $_POST["nota3"] != null) ? $_POST["nota3"] : "";
     $nota4 = (isset($_POST["nota4"]) && $_POST["nota4"] != null) ? $_POST["nota4"] : "";
     $media = (isset($_POST["media"]) && $_POST["media"] != null) ? $_POST["media"] : "";
 } else if (!isset($id)) {
     // Se não se não foi setado nenhum valor para variável $id
     $id = (isset($_GET["id"]) && $_GET["id"] != null) ? $_GET["id"] : "";
     $nome = NULL;
     $idade = NULL;
     $turma = NULL;
     $nota1 = NULL;
     $nota2 = NULL;
     $nota3 = NULL;
     $nota4 = NULL;
     $media = NULL;
 }
 try {
    $conexao = new PDO("mysql:host=127.0.0.1; dbname=crudsimples", "root");
    $conexao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conexao->exec("set names utf8");
} catch (PDOException $erro) {
    echo "Erro na conexão:" . $erro->getMessage();
}
if (isset($_REQUEST["act"]) && $_REQUEST["act"] == "save" && $nome != "") {
    try {
        if ($id != "") {
            $stmt = $conexao->prepare("UPDATE alunos SET nome=?, idade=?, turma=?, nota1=?, nota2=?, nota3=?, nota4=?, media=? WHERE id = ?");
            $stmt->bindParam(9, $id);
        } else {
            $stmt = $conexao->prepare("INSERT INTO alunos (nome, idade, turma, nota1, nota2, nota3, nota4, media) VALUES (?, ?, ?, ?, ?, ?, ?,?)");
        }
        $media = ($nota1 + $nota2 + $nota3 + $nota4) / 4;
        $stmt->bindParam(1, $nome);
        $stmt->bindParam(2, $idade);
        $stmt->bindParam(3, $turma);
        $stmt->bindParam(4, $nota1);
        $stmt->bindParam(5, $nota2);
        $stmt->bindParam(6, $nota3);
        $stmt->bindParam(7, $nota4);
        $stmt->bindParam(8, $media);
        
        if ($stmt->execute()) {
            if ($stmt->rowCount() > 0) {
                echo "<div id ='alert'><br>Dados cadastrados com sucesso!</div>";
                $nome = NULL;
                $idade = NULL;
                $turma = NULL;
                $nota1 = NULL;
                $nota2 = NULL;
                $nota3 = NULL;
                $nota4 = NULL;
            } else {
                echo "<div id ='alert'><br>Erro ao tentar efetivar cadastro</div>";
            }
        } else {
            throw new PDOException("Erro: Não foi possível executar a declaração sql");
        }
    } catch (PDOException $erro) {
        echo "Erro: ".$erro->getMessage();
    }
}
if (isset($_REQUEST["act"]) && $_REQUEST["act"] == "upd" && $id != "") {
    try {
        $stmt = $conexao->prepare("SELECT * FROM alunos WHERE id = ?");
        $stmt->bindParam(1, $id, PDO::PARAM_INT);
        if ($stmt->execute()) {
            $rs = $stmt->fetch(PDO::FETCH_OBJ);
            $id = $rs->id;
            $nome = $rs->nome;
            $idade = $rs->idade;
            $turma = $rs->turma;
            $nota1 = $rs->nota1;
            $nota2 = $rs->nota2;
            $nota3 = $rs->nota3;
            $nota4 = $rs->nota4;
        } else {
            throw new PDOException("Erro: Não foi possível executar a declaração sql");
        }
        
    } catch (PDOException $erro) {
        echo "Erro: ".$erro->getMessage();
    }
}
if (isset($_REQUEST["act"]) && $_REQUEST["act"] == "del" && $id != "") {
    try {
        $stmt = $conexao->prepare("DELETE FROM alunos WHERE id = ?");
        $stmt->bindParam(1, $id, PDO::PARAM_INT);
        if ($stmt->execute()) {
        echo "<div id ='alert'><br>Registo foi excluído com êxito!</div>";
            $id = null;
        } else {
            throw new PDOException("Erro: Não foi possível executar a declaração sql");
        }
    } catch (PDOException $erro) {
        echo "Erro: ".$erro->getMessage();
    }
}
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Dados de alunos</title>
            <link rel="stylesheet" type="text/css" href="style.css">
    </head>
    <body>
    <form action="?act=save" method="POST" name="form1" >
        <br>
            <h1>&nbsp;</h1>
            <hr>
            <input type="hidden" name="id" <?php
            // Preenche o id no campo id com um valor "value"
            if (isset($id) && $id != null || $id != "") {
                echo "value=\"{$id}\"";
            }
            ?> />
            Nome:
            <input type="text" name="nome" <?php
            // Preenche o nome no campo nome com um valor "value"
            if (isset($nome) && $nome != null || $nome != ""){
                echo "value=\"{$nome}\"";
            }
            ?> />
            &nbsp;&nbsp; Idade:
            <input type="text" name="idade" <?php
            if (isset($idade) && $idade != null || $idade != ""){
                echo "value=\"{$idade}\"";
            }
            ?> />
            &nbsp; Turma:
            <input type="text" name="turma" <?php
            // Preenche o email no campo email com um valor "value"
            if (isset($turma) && $turma != null || $turma != ""){
                echo "value=\"{$turma}\"";
            }
            ?> />
            <br>
            <br>
            1° Bimentre:
            <input type="text" name="nota1" <?php
            if (isset($nota1) && $nota1 != null || $nota1 != ""){
                echo "value=\"{$nota1}\"";
            }
            ?> />
            
            &nbsp;   2° Bimentre:
            <input type="text" name="nota2" <?php
            if (isset($nota2) && $nota2 != null || $nota2 != ""){
                echo "value=\"{$nota2}\"";
            }
            ?> />
            &nbsp;   3° Bimentre:
            <input type="text" name="nota3" <?php
            if (isset($nota3) && $nota3 != null || $nota3 != ""){
                echo "value=\"{$nota3}\"";
            }
            ?> />
            &nbsp;   4° Bimentre:
            <input type="text" name="nota4" <?php
            if (isset($nota4) && $nota4 != null || $nota4 != ""){
                echo "value=\"{$nota4}\"";
            }
            ?> />
            &nbsp;
           <input id= "b1" type="submit" value="Salvar" />
           &nbsp;
           <input id= "b2" type="reset" value="Novo" />
           <hr>
        </form>
        <table border="1" width="100%">
    <tr>
        <th>Nome</th>
        <th>Idade</th>
        <th>Turma</th>
        <th>Nota 1</th>
        <th>Nota 2</th>
        <th>Nota 3</th>
        <th>Nota 4</th>
        <th>Media</th>
        <th>Ações</th>
    </tr>
    <?php
try {
 
    $stmt = $conexao->prepare("SELECT * FROM alunos ORDER BY turma");
 
        if ($stmt->execute()) {
            while ($rs = $stmt->fetch(PDO::FETCH_OBJ)) {
                //$media = ($nota1 + $nota2 + $nota3 + $nota4) / 4;
                echo "<tr>";
                echo "<td>".$rs->nome."</td><td>".$rs->idade."</td><td>".$rs->turma."</td><td>".$rs->nota1."</td>
                <td>".$rs->nota2."</td><td>".$rs->nota3."</td><td>".$rs->nota4."</td><td>".$rs->media."</td>
                <td><center><a href=\"?act=upd&id=" . $rs->id . "\"><button>Alterar</button></a>"
                ."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"
                ."<a href=\"?act=del&id=" . $rs->id . "\"><button color='primary'>Excluir</button></a></center></td>";
                echo "</tr>";
            }
        } else {
            echo "Erro: Não foi possível recuperar os dados do banco de dados";
        }
} catch (PDOException $erro) {
    echo "Erro: ".$erro->getMessage();
}
?>
</table>
    </body>
</html>