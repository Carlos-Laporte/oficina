<?php
    require_once("../conexao.php");

    $erro = '';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $placa = trim($_POST['placa'] ?? '');
        $modelo = trim($_POST['modelo'] ?? '');
        $marca = trim($_POST['marca'] ?? '');
        $ano = trim($_POST['ano'] ?? '');
        $cor = trim($_POST['cor'] ?? '');
        $cliente_id = trim($_POST['cliente_id']);

        if (empty($placa) || empty($modelo) || empty($marca) || empty($ano) || empty($cor) || empty($cliente_id)) {

            $erro = 'Preencha todos os campos.';

        } else {
            
            $sql = "INSERT INTO veiculo
                    (placa, modelo, marca, ano, cor, cliente_id
                ) VALUES 
                    (:placa, :modelo, :marca, :ano, :cor, :cliente_id)";

            $stmt = $conn->prepare($sql);
            $stmt->bindValue(':placa', $placa);
            $stmt->bindValue(':modelo', $modelo);
            $stmt->bindValue(':marca', $marca);
            $stmt->bindValue(':ano', $ano);
            $stmt->bindValue(':cor', $cor);
            $stmt->bindValue(':cliente_id', $cliente_id);

            try {
                $stmt->execute();
                header("Location: ../index.php?sucesso=3");
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

<?php
    $clientes = $conn->query("SELECT id, nome FROM cliente")->fetchAll();
?>