<?php
session_start();
require('../db.php');

// Beveiligingscheck: controleer of gebruiker admin is
if (!isset($_SESSION['gebruiker_id']) || $_SESSION['gebruiker_rol'] !== 'admin') {
    header('Location: ../index.php');
    exit;
}

$melding = '';
$fout = '';

// Verwerk POST-verzoeken
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['actie'])) {
        
        // VOEG NIEUWE REIS TOE
        if ($_POST['actie'] === 'toevoegen') {
            $bestemming = $_POST['bestemming'] ?? '';
            $beschrijving = $_POST['beschrijving'] ?? '';
            $type_reis = $_POST['type_reis'] ?? '';
            $prijs = $_POST['prijs'] ?? '';
            $afbeelding_url = $_POST['afbeelding_url'] ?? '';
            $startdatum = $_POST['startdatum'] ?? '';
            $einddatum = $_POST['einddatum'] ?? '';
            $max_personen = $_POST['max_personen'] ?? '';
            
            // Validatie
            if (empty($bestemming) || empty($beschrijving) || empty($type_reis) || empty($prijs) || empty($startdatum) || empty($einddatum) || empty($max_personen)) {
                $fout = 'Vul alstublieft alle verplichte velden in.';
            } else {
                try {
                    $sql = "INSERT INTO reizen (bestemming, beschrijving, type_reis, prijs, afbeelding_url, startdatum, einddatum, max_personen) 
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute([
                        $bestemming,
                        $beschrijving,
                        $type_reis,
                        $prijs,
                        $afbeelding_url,
                        $startdatum,
                        $einddatum,
                        $max_personen
                    ]);
                    $melding = 'Reis succesvol toegevoegd!';
                } catch (PDOException $e) {
                    $fout = 'Fout bij het toevoegen van de reis: ' . $e->getMessage();
                }
            }
        }
        
        // VERWIJDER REIS
        elseif ($_POST['actie'] === 'verwijderen') {
            $reis_id = $_POST['reis_id'] ?? 0;
            
            if ($reis_id > 0) {
                try {
                    $sql = "DELETE FROM reizen WHERE id = ?";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute([$reis_id]);
                    $melding = 'Reis succesvol verwijderd!';
                } catch (PDOException $e) {
                    $fout = 'Fout bij het verwijderen van de reis: ' . $e->getMessage();
                }
            }
        }
        
        // BEWERK REIS
        elseif ($_POST['actie'] === 'bewerken') {
            $reis_id = $_POST['reis_id'] ?? 0;
            $bestemming = $_POST['bestemming'] ?? '';
            $beschrijving = $_POST['beschrijving'] ?? '';
            $type_reis = $_POST['type_reis'] ?? '';
            $prijs = $_POST['prijs'] ?? '';
            $afbeelding_url = $_POST['afbeelding_url'] ?? '';
            $startdatum = $_POST['startdatum'] ?? '';
            $einddatum = $_POST['einddatum'] ?? '';
            $max_personen = $_POST['max_personen'] ?? '';
            
            // Validatie
            if (empty($bestemming) || empty($beschrijving) || empty($type_reis) || empty($prijs) || empty($startdatum) || empty($einddatum) || empty($max_personen)) {
                $fout = 'Vul alstublieft alle verplichte velden in.';
            } else {
                try {
                    $sql = "UPDATE reizen SET bestemming = ?, beschrijving = ?, type_reis = ?, prijs = ?, afbeelding_url = ?, startdatum = ?, einddatum = ?, max_personen = ? 
                            WHERE id = ?";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute([
                        $bestemming,
                        $beschrijving,
                        $type_reis,
                        $prijs,
                        $afbeelding_url,
                        $startdatum,
                        $einddatum,
                        $max_personen,
                        $reis_id
                    ]);
                    $melding = 'Reis succesvol bijgewerkt!';
                } catch (PDOException $e) {
                    $fout = 'Fout bij het bijwerken van de reis: ' . $e->getMessage();
                }
            }
        }
    }
}

// Haal alle reizen op
$sql = "SELECT * FROM reizen ORDER BY startdatum ASC";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$alle_reizen = $stmt->fetchAll();

// Controleer of er een reis te bewerken is
$reis_bewerken = null;
if (isset($_GET['bewerk_id'])) {
    $bewerk_id = $_GET['bewerk_id'];
    $sql = "SELECT * FROM reizen WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$bewerk_id]);
    $reis_bewerken = $stmt->fetch();
}
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/stylesheet.css">
    <title>Admin – Reizen beheren</title>
</head>
<body>
    <?php require('../includes/header.php'); ?>
    
    <div class="admin-container">
        <div class="admin-header">
            <h1>🔧 Reizen beheren</h1>
            <a href="../reizen.php">← Terug naar reizen</a>
        </div>
        
        <?php if ($melding): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($melding); ?></div>
        <?php endif; ?>
        
        <?php if ($fout): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($fout); ?></div>
        <?php endif; ?>
        
        <!-- FORMULIER VOOR TOEVOEGEN / BEWERKEN -->
        <div class="form-section">
            <h2><?php echo $reis_bewerken ? 'Reis bewerken' : 'Nieuwe reis toevoegen'; ?></h2>
            
            <form method="POST">
                <input type="hidden" name="actie" value="<?php echo $reis_bewerken ? 'bewerken' : 'toevoegen'; ?>">
                <?php if ($reis_bewerken): ?>
                    <input type="hidden" name="reis_id" value="<?php echo $reis_bewerken['id']; ?>">
                <?php endif; ?>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="bestemming">Bestemming *</label>
                        <input type="text" id="bestemming" name="bestemming" required 
                               value="<?php echo $reis_bewerken ? htmlspecialchars($reis_bewerken['bestemming']) : ''; ?>">
                    </div>
                    <div class="form-group">
                        <label for="type_reis">Type reis *</label>
                        <select id="type_reis" name="type_reis" required>
                            <option value="">-- Kies een type --</option>
                            <option value="Citytrip" <?php echo ($reis_bewerken && $reis_bewerken['type_reis'] === 'Citytrip') ? 'selected' : ''; ?>>Citytrip</option>
                            <option value="Strand" <?php echo ($reis_bewerken && $reis_bewerken['type_reis'] === 'Strand') ? 'selected' : ''; ?>>Strand</option>
                            <option value="Avontuur" <?php echo ($reis_bewerken && $reis_bewerken['type_reis'] === 'Avontuur') ? 'selected' : ''; ?>>Avontuur</option>
                        </select>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="beschrijving">Beschrijving *</label>
                    <textarea id="beschrijving" name="beschrijving" required><?php echo $reis_bewerken ? htmlspecialchars($reis_bewerken['beschrijving']) : ''; ?></textarea>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="prijs">Prijs (€) *</label>
                        <input type="number" id="prijs" name="prijs" step="0.01" min="0" required 
                               value="<?php echo $reis_bewerken ? htmlspecialchars($reis_bewerken['prijs']) : ''; ?>">
                    </div>
                    <div class="form-group">
                        <label for="max_personen">Max personen *</label>
                        <input type="number" id="max_personen" name="max_personen" min="1" required 
                               value="<?php echo $reis_bewerken ? htmlspecialchars($reis_bewerken['max_personen']) : ''; ?>">
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="startdatum">Startdatum *</label>
                        <input type="date" id="startdatum" name="startdatum" required 
                               value="<?php echo $reis_bewerken ? htmlspecialchars($reis_bewerken['startdatum']) : ''; ?>">
                    </div>
                    <div class="form-group">
                        <label for="einddatum">Einddatum *</label>
                        <input type="date" id="einddatum" name="einddatum" required 
                               value="<?php echo $reis_bewerken ? htmlspecialchars($reis_bewerken['einddatum']) : ''; ?>">
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="afbeelding_url">Afbeelding URL</label>
                    <input type="text" id="afbeelding_url" name="afbeelding_url" 
                           value="<?php echo $reis_bewerken ? htmlspecialchars($reis_bewerken['afbeelding_url']) : ''; ?>"
                           placeholder="/images/bestemming.jpg">
                </div>
                
                <div class="form-buttons">
                    <button type="submit" class="btn btn-primary">
                        <?php echo $reis_bewerken ? '💾 Wijzigingen opslaan' : '➕ Reis toevoegen'; ?>
                    </button>
                    <?php if ($reis_bewerken): ?>
                        <a href="reizen.php" class="btn btn-secondary">Annuleren</a>
                    <?php endif; ?>
                </div>
            </form>
        </div>
        
        <!-- TABEL MET ALLE REIZEN -->
        <div class="table-section">
            <h2>📋 Alle reizen (<?php echo count($alle_reizen); ?>)</h2>
            
            <?php if (count($alle_reizen) > 0): ?>
                <div class="table-wrapper">
                    <table class="reizen-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Bestemming</th>
                                <th>Type</th>
                                <th>Prijs</th>
                                <th>Startdatum</th>
                                <th>Einddatum</th>
                                <th>Plaatsen</th>
                                <th>Geboekt</th>
                                <th>Acties</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($alle_reizen as $reis): ?>
                                <tr>
                                    <td><?php echo $reis['id']; ?></td>
                                    <td><strong><?php echo htmlspecialchars($reis['bestemming']); ?></strong></td>
                                    <td><?php echo htmlspecialchars($reis['type_reis']); ?></td>
                                    <td class="prijs-cel">€ <?php echo number_format($reis['prijs'], 2, ',', '.'); ?></td>
                                    <td><?php echo date('d-m-Y', strtotime($reis['startdatum'])); ?></td>
                                    <td><?php echo date('d-m-Y', strtotime($reis['einddatum'])); ?></td>
                                    <td><?php echo $reis['max_personen']; ?></td>
                                    <td><?php echo $reis['geboekt_personen']; ?> / <?php echo $reis['max_personen']; ?></td>
                                    <td>
                                        <div class="actie-knoppen">
                                            <a href="reizen.php?bewerk_id=<?php echo $reis['id']; ?>" class="btn btn-edit">✏️ Bewerk</a>
                                            <form method="POST" class="delete-form" onsubmit="return confirm('Weet je zeker dat je deze reis wilt verwijderen?');">
                                                <input type="hidden" name="actie" value="verwijderen">
                                                <input type="hidden" name="reis_id" value="<?php echo $reis['id']; ?>">
                                                <button type="submit">🗑️ Verwijder</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="empty-message">
                    <p>Er zijn momenteel geen reizen in de database.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
    
    <?php require('../includes/footer.php'); ?>
</body>
</html>
