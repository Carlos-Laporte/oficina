<?php
    session_start();
    $error_message = '';
    $success_message = '';

    require_once('../configuration/connection.php');

    //recebe os dados do formulário de cadastro
    if ($_SERVER["REQUEST_METHOD"] === "POST") {

        $nome    = trim($_POST['nome']);
        $email = strtolower(trim($_POST['email']));
        $telefone = trim($_POST['telefone']);
        $veiculo = trim($_POST['veiculo']);
        $ano = trim($_POST['ano']);
        $servico = trim($_POST['servico']);
        $data = trim($_POST['data']);
        $horario = trim($_POST['horario']);
        $comentario = trim($_POST['comentario']);

        if (empty($supplierName) || empty($location) || empty($email)) {
            $error_message = "Please fill in all fields.";

        } else {
            $stmt = $conn->prepare("SELECT id FROM suppliers WHERE email = :email");
            $stmt->execute([':email' => $email]);
            if ($stmt->rowCount() > 0) {
                $error_message = "This email is already registered.";
            } 
            else {
                try {

                    $sql = "INSERT INTO suppliers (`supplier_name`, `supplier_location`, email, created_by) 
                            VALUES (:supplier_name, :supplier_location, :email, :created_by)";
                    $stmt = $conn->prepare($sql);
                    
                    $result = $stmt->execute([
                        ':supplier_name'      => $supplierName,
                        ':supplier_location'  => $location,
                        ':email'              => $email
                    ]);

                    if($result) {
                        $_SESSION['success_message'] = "Supplier created successfully.";
                        header("Location: suppliers_add.php");
                        exit();
                    }
    
                } catch (PDOException $e) {
                    $error_message = "Database Error: " . $e->getMessage();
                }
            }
        }
    }
?>

<!DOCTYPE html>
<html lang="pt">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Lelicar Centro Automotivo</title>
        <link rel="stylesheet" href="../assets/css/styleIndex.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    </head>
    <body>
        <header>
            <div class="abaSuperior">
                <div class="logo">
                    <img src="../assets/img/logo.jpg" alt="Logo">
                </div>
                <a href="#inicio" class="navLink">INÍCIO</a>
                <a href="#servicos" class="navLink">SERVIÇOS</a>
                <a href="#equipe" class="navLink">EQUIPE</a>
                <a href="#depoimentos" class="navLink">DEPOIMENTOS</a>
                <a href="#contato" class="navLink">CONTATO</a>
                <a href="../admin/login.php" class="agendamento">LOGIN</a>
            </div>
        </header>
        <main>
            <div class="banner" id="inicio">
                <div class="corpoBanner">
                    <div class="cabecaBanner">
                        <h2><i class="bi bi-car-front-fill"></i> QUALIDADE, CONFIANÇA E DESEMPENHO</h2>
                        <h1>CUIDA DO SEU CARRO <br><span class="destaque">COM QUEM ENTENDE</span></h1>
                        <p>
                            Aqui seu veículo recebe o melhor atendimento,<br>com profissionais qualificados,
                            peças de qualidade,<br>tecnologia de ponta e um ambiente seguro.
                        </p>
                        <a href="#agendamento" class="agendamento"><i class="bi bi-calendar2-week"></i> AGENDAR</a>
                    </div>
                    <div class="informacoesBanner">
                        <div class="servicosDeQualidade">
                            <h3><i class="bi bi-wrench"></i> Serviços de Qualidade</h3>
                            <p>
                                Oferecemos uma ampla gama de serviços para atender às necessidades
                                do seu veículo, desde manutenção preventiva até reparos complexos.
                            </p>
                        </div>
                        <div class="equipamentosModernos">
                            <h3><i class="bi bi-tools"></i> Equipamentos Modernos</h3>
                            <p>
                                Contamos com equipamentos de última geração para garantir diagnósticos
                                precisos e reparos eficientes.
                            </p>
                        </div>
                        <div class="atendimentoHonesto">
                            <h3><i class="bi bi-person-check"></i> Atendimento Honesto</h3>
                            <p>
                                Prezamos pela transparência e honestidade em todos os nossos serviços.
                            </p>
                        </div>
                        <div class="garantiaDeServicos">
                            <h3><i class="bi bi-shield-check"></i> Garantia de Serviços</h3>
                            <p>
                                Oferecemos garantia em todos os nossos serviços, proporcionando tranquilidade
                                e confiança.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="servicos" id="servicos">
                <div class="textoServicos">
                    <p>NOSSOS SERVIÇOS</p><br>
                    <h2>SOLUÇÕES COMPLETAS PARA SEU VEÍCULO</h2>
                </div>
                <div class="linha"><strong>________</strong></div>
                <div class="corpoServicos">
                    <div class="tiposDeServicos">
                        <span class="icons"><i class="bi bi-gear-wide-connected"></i></span>
                        <h3 class="tituloServico">MANUTENÇÃO PREVENTIVA</h3>
                        <p class="informacoesServicos">
                            Manutenção preventiva e corretiva com diagnóstico preciso.
                        </p>
                    </div>
                    <div class="tiposDeServicos">
                        <span class="icons"><i class="bi bi-laptop"></i></span>
                        <h3 class="tituloServico">DIAGNÓSTICOS ELETRÔNICOS</h3>
                        <p class="informacoesServicos">
                            Equipamentos modernos para diagnósticos precisos.
                        </p>
                    </div>
                    <div class="tiposDeServicos">
                        <span class="icons"><i class="bi bi-disc"></i></span>
                        <h3 class="tituloServico">FREIOS</h3>
                        <p class="informacoesServicos">
                            Pastilhas, discos, tambores e todo o sistema de freios.
                        </p>
                    </div>
                    <div class="tiposDeServicos">
                        <span class="icons"><i class="bi bi-wrench-adjustable"></i></span>
                        <h3 class="tituloServico">SUSPENSÃO</h3>
                        <p class="informacoesServicos">
                            Amortecedores, molas, buchas e componentes de suspensão.
                        </p>
                    </div>
                    <div class="tiposDeServicos">
                        <span class="icons"><i class="bi bi-droplet-fill"></i></span>
                        <h3 class="tituloServico">TROCA DE ÓLEO</h3>
                        <p class="informacoesServicos">
                            Troca de óleo e filtros com produtos de qualidade para maior proteção do motor.
                        </p>
                    </div>
                    <div class="tiposDeServicos">
                        <span class="icons"><i class="bi bi-thermometer-snow"></i></span>
                        <h3 class="tituloServico">AR CONDICIONADO</h3>
                        <p class="informacoesServicos">
                            Higienização, carga de gás e manutenção completa do sistema.
                        </p>
                    </div>
                </div>
            </div>
            <div class="equipe" id="equipe">
                <div class="informacoesEquipe">
                    <p class="textoEquipe">NOSSA EQUIPE</p>
                    <h3 class="tituloEquipe">PROFISSIONAIS QUE ENTENDEM DO QUE FAZEM</h3>
                </div>
                <div class="corpoEquipe">
                    <div class="membrosEquipe">
                        <img class="imgEquipe" src="../assets/img/mecanico_chefe.png" alt="Rafael Souza">
                        <h4>RAFAEL SOUZA</h4>
                        <p class="cargo">Mecânico Chefe</p>
                        <p>
                            Mais de 15 anos de experiência em mecânica nacional e importada.
                        </p>
                    </div>
                    <div class="membrosEquipe">
                        <img class="imgEquipe" src="../assets/img/especialista.png" alt="Lucas Ferreira">
                        <h4>LUCAS FERREIRA</h4>
                        <p class="cargo">Especialista em Diagnóstico</p>
                        <p>
                            Especialista em diagnósticos elétricos e injeção eletrônica com ampla experiência.
                        </p>
                    </div>
                    <div class="membrosEquipe">
                        <img class="imgEquipe" src="../assets/img/especialista_mecanico.png" alt="Diego Almeida">
                        <h4>DIEGO ALMEIDA</h4>
                        <p class="cargo">Mecânico Especializado</p>
                        <p>
                            Especializado em sistemas de transmissão, com conhecimento geral.
                        </p>
                    </div>
                    <div class="membrosEquipe">
                        <img class="imgEquipe" src="../assets/img/mecanico.png" alt="Carlos Oliveira">
                        <h4>CARLOS OLIVEIRA</h4>
                        <p class="cargo">Mecânico</p>
                        <p>
                            Tem experiência em manutenção geral de veículos,
                            incluindo troca de óleo, freios e suspensão.
                        </p>
                    </div>
                    <div class="membrosEquipe">
                        <img class="imgEquipe" src="../assets/img/atendente.png" alt="Juliana Lima">
                        <h4>JULIANA LIMA</h4>
                        <p class="cargo">Atendimento</p>
                        <p>
                            Atendimento ao cliente, com habilidades
                            em comunicação e resolução de problemas.
                        </p>
                    </div>
                </div>
            </div>
            <div class="depoimentos" id="depoimentos">
                <div class="textoDepoimentos">
                    <p>DEPOIMENTOS</p><br>
                    <h2>VEJA O QUE NOSSOS CLIENTES DIZEM SOBRE NÓS</h2>
                </div>
                <div class="corpoDepoimentos">
                    <div class="tiposDeDepoimentos">
                        <p class="informacoesDepoimentos"><i class="bi bi-quote"></i>
                            Excelente atendimento e serviço de alta qualidade! Resolveram o
                            problema do meu carro rápido e com preço justo.
                        </p>
                        <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i><i class="bi bi-star>-fill"></i>
                        <p class="tituloServico"><strong>Carlos A.</strong><br>Cliente desde 2022</p>
                    </div>
                    <div class="tiposDeDepoimentos">
                        <p class="informacoesDepoimentos"><i class="bi bi-quote"></i>
                            Profissionais muito competentes e atenciosos. Meu carro ficou
                            como novo depois da manutenção. Recomendo a todos!
                        </p>
                        <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i><i class="bi bi-star>-fill"></i>
                        <p class="tituloServico"><strong>Maria R.</strong><br>Cliente desde 2023</p>
                    </div>
                    <div class="tiposDeDepoimentos">
                        <p class="informacoesDepoimentos"><i class="bi bi-quote"></i>
                            Atendimento excelente e serviço de alta qualidade. Recomendo a
                            todos que precisam de um mecânico confiável.
                        </p>
                        <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i><i class="bi bi-star>-fill"></i>
                        <p class="tituloServico"><strong>João S.</strong><br>Cliente desde 2021</p>
                    </div>
                </div>
            </div>
            <div class="corpoAgendamento" id="agendamento">
                <div class="textoAgendamento" id="contato">
                    <p class="textoTop"><strong>AGENDE SUA VISITA</strong></p>
                    <h3 class="formTitle">É rápido, fácil e garante o melhor atendimento para o seu veículo</h3>
                    <p class="formDescription">
                        Preencha o formulário ao lado ou entre em contato conosco para
                        agendar uma visita ou tirar dúvidas sobre nossos serviços. 
                        Estamos aqui para ajudar a cuidar do seu carro com qualidade e confiança.
                    </p>
                    <p><i class="bi bi-telephone-fill"></i> (11) 1234-5678</p>
                    <p><i class="bi bi-whatsapp"></i> (11) 98765-4321</p>
                    <p><i class="bi bi-geo-alt-fill"></i> Rua das Oficinas, 123 - Vila Industrial, São Paulo - SP</p>
                </div>
                <div class="formAgendamento">
                    <h2 class="formTitulo">FORMULÁRIO DE AGENDAMENTO</h2>
                    <form action="index.php" method="$_POST">
                        <div class="formConteiner">
                            <input type="text" placeholder="Nome Completo" id="nome" required>
                            <input type="email" placeholder="Email" id="email" required>
                            <input type="tel" placeholder="Telefone" id="telefone" required>
                            <input type="text" placeholder="Veículo (marca e modelo)" id="veiculo" required>
                            <input type="text" placeholder="Ano do Veículo" id="ano" required>
                            <select type="text" placeholder="Serviço Desejado" id="servico" required>
                                <option value="">Selecione um serviço</option>
                                <option value="manutencao">Manutenção</option>
                                <option value="reparo">Reparo</option>
                                <option value="diagnostico">Diagnóstico</option>
                            </select>
                            <input type="date" placeholder="Data da Visita" id="data" required>
                            <input type="time" placeholder="Horário preferido" id="horario" required>
                            <textarea placeholder="Observações adicionais" rows="4" id="comentario" ></textarea>
                            <button class="agendamento">AGENDAR VISITA</button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
        <footer class="footer">
            <div class="corpoFooter">
                <p>&copy; 2024 Lelicar Centro Automotivo. Todos os direitos reservados.</p>
                <div class="linksFooter">
                    <a href=""><i class="bi bi-instagram"></i></a>
                    <a href=""><i class="bi bi-facebook"></i></a>
                    <a href=""><i class="bi bi-whatsapp"></i></a>
                </div>
            </div>
        </footer>
    </body>
</html>