<?php
    require_once("../conexao.php");

    $erro = '';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $nome = trim($_POST['nome'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $telefone = trim($_POST['telefone'] ?? '');
        $veiculo = trim($_POST['veiculo'] ?? '');
        $ano = trim($_POST['ano'] ?? '');
        $servico = trim($_POST['servico'] ?? '');
        $data = trim($_POST['data'] ?? '');
        $horario = trim($_POST['horario'] ?? '');
        $comentario = trim($_POST['comentario'] ?? '');

        if (empty($nome) || empty($email) || empty($telefone) || empty($veiculo) || empty($ano) || empty($servico) || empty($data) || empty($horario)) {

            $erro = 'Preencha todos os campos.';

        } else {
            
            $sql = "INSERT INTO agendamento
                    (nome, email, telefone, veiculo, ano, servico, data, horario, comentario
                ) VALUES 
                    (:nome, :email, :telefone, :veiculo, :ano, :servico, :data, :horario, :comentario)";

            $stmt = $conn->prepare($sql);
            $stmt->bindValue(':nome', $nome);
            $stmt->bindValue(':email', $email);
            $stmt->bindValue(':telefone', $telefone);
            $stmt->bindValue(':veiculo', $veiculo);
            $stmt->bindValue(':ano', $ano);
            $stmt->bindValue(':servico', $servico);
            $stmt->bindValue(':data', $data);
            $stmt->bindValue(':horario', $horario);
            $stmt->bindValue(':comentario', $comentario);

            try {
                $stmt->execute();
                header("Location: ../public/index.php?sucesso=1");
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