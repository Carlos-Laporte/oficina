<?php
    require_once("../../conexao.php");

    $erro = '';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $nome = trim($_POST['nome'] ?? '');
        $cpf = trim($_POST['cpf'] ?? '');
        $telefone = trim($_POST['telefone'] ?? '');
        $email = trim($_POST['email'] ?? '');
        

        if (empty($nome) || empty($cpf) || empty($telefone) || empty($email)) {

            $erro = 'Preencha todos os campos.';

        } else {
            
            $sql = "INSERT INTO cliente
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


<form action="veiculos.php" method="POST">
    <label for="">Clinte</label><br>
    <input type="text" name="nome" placeholder="Nome completo" required><br>
    <label for="">CPF</label><br>
    <input type="text" name="cpf" placeholder="000.000.000-00" required><br>
    <label for="">Telefone</label><br>
    <input type="text" name="telefone" placeholder="(00) 00000-0000" required><br>
    <label for="">Email</label><br>
    <input type="email" name="email" placeholder="exemplo@gmail.com" required><br>
    <button type="submit"> Salvar veículo</button>
</form>