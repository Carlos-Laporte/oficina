<?php
    require_once("../../conexao.php");

    $erro = '';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $placa = trim($_POST['placa'] ?? '');
        $modelo = trim($_POST['modelo'] ?? '');
        $marca = trim($_POST['marca'] ?? '');
        $ano = trim($_POST['ano'] ?? '');
        $cor = trim($_POST['cor'] ?? '');
        $chassi = trim($_POST['chassi'] ?? '');
        $cliente_id = trim($_POST['cliente_id']);

        if (empty($placa) || empty($modelo) || empty($marca) || empty($ano) || empty($cor) || empty($cliente_id)) {

            $erro = 'Preencha todos os campos.';

        } else {
            
            $sql = "INSERT INTO veiculo
                    (placa, modelo, marca, ano, cor, chassi, cliente_id
                ) VALUES 
                    (:placa, :modelo, :marca, :ano, :cor, :chassi, :cliente_id)";

            $stmt = $conn->prepare($sql);
            $stmt->bindValue(':placa', $placa);
            $stmt->bindValue(':modelo', $modelo);
            $stmt->bindValue(':marca', $marca);
            $stmt->bindValue(':ano', $ano);
            $stmt->bindValue(':cor', $cor);
            $stmt->bindValue(':chassi', $chassi);
            $stmt->bindValue(':cliente_id', $cliente_id);

            try {
                $stmt->execute();
                header("Location: paginaVeiculos.php?sucesso=3");
                exit;
            } catch (PDOException $e) {
                $erro = $e->getMessage();
            }
        }
    }
?>

<?php if (isset($_GET['sucesso'])): ?>
    <p>Veículo cadastrado com sucesso!</p>
<?php endif; ?>

<?php if (!empty($erro)): ?>
    <p><?= $erro ?></p>
<?php endif; ?>

<?php
    $clientes = $conn->query("SELECT id, nome FROM cliente")->fetchAll();
?>

<form action="paginaVeiculos.php" method="POST">
    <label for="">Clinte</label><br>
    <select name="cliente_id" id="cliente_id">
        <option value="">Selecione o cliente</option>
        <?php foreach($clientes as $cliente): ?>
            <option value="<?= $cliente['id'] ?>">
                <?= $cliente['nome'] ?>
            </option>
        <?php endforeach; ?>
    </select><br>
    <label for="">Placa</label><br>
    <input type="text" name="placa" placeholder="Digite a placa do seu veículo" minlength="7" maxlength="8" required><br>
    <label for="">Modelo</label><br>
    <input type="text" name="modelo" placeholder="Digite o modelo" required><br>
    <label for="">Marca</label><br>
    <input type="text" name="marca" placeholder="Marca do veículo" required><br>
    <label for="">Ano</label><br>
    <input type="number" name="ano" placeholder="Ano do veículo" min="1900" max="2026" required><br>
    <label for="">Cor</label><br>
    <input type="text" name="cor" placeholder="Cor do veículo" required><br>
    <label for="">Chassi (Opcional)</label><br>
    <input type="text" name="chassi" placeholder="Digite o número do chassi" minlength="17" maxlength="17"><br>
    <button type="submit"> Salvar veículo</button>
</form>