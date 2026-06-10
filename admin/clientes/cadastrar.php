<?php
    require_once("../conexao.php");

    $erro = '';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $nome = trim($_POST['nome'] ?? '');
        $cpf = trim($_POST['cpf'] ?? '');
        $telefone = trim($_POST['telefone'] ?? '');
        $email = trim($_POST['email'] ?? '');
        

        if (empty($nome) || empty($cpf) || empty($telefone) || empty($email)) {

            $erro = 'Preencha todos os campos.';

        } else {
            
            $sql = "INSERT INTO agendamento
                    (nome, cpf, telefone, email
                ) VALUES 
                    (:nome, :cpf, :telefone, :email)";

            $stmt = $conn->prepare($sql);
            $stmt->bindValue(':nome', $nome);
            $stmt->bindValue(':cpf', $cpf);
            $stmt->bindValue(':telefone', $telefone);
            $stmt->bindValue(':email', $email);

            try {
                $stmt->execute();
                header("Location: ../index.php?sucesso=2");
                exit;
            } catch (PDOException $e) {
                $erro = $e->getMessage();
            }
        }
    }
?>

<?php if (isset($_GET['sucesso'])): ?>
    <p>Agendamento cadastrado com sucesso!</p>
<?php endif; ?>

<?php if (!empty($erro)): ?>
    <p><?= $erro ?></p>
<?php endif; ?>