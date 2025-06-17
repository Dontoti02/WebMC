<?php
$cursos = [
    ['id' => 1, 'nombre' => 'Matemática', 'profesor' => 'Carlos Mendoza', 'descripcion' => 'Curso de álgebra lineal y cálculo diferencial', 'progreso' => 0, 'color' => '#4CAF50'],
    ['id' => 2, 'nombre' => 'Ciencias Sociales', 'profesor' => 'Ana Torres', 'descripcion' => 'Análisis de obras literarias del siglo XXI', 'progreso' => 0, 'color' => '#8BC34A'],
    ['id' => 3, 'nombre' => 'Ciencia tecnologia y Ambiente ', 'profesor' => 'Luisa Fernández', 'descripcion' => 'Biología, química y física aplicada', 'progreso' => 0, 'color' => '#CDDC39'],
    ['id' => 4, 'nombre' => 'Historia', 'profesor' => 'Lidia Castillo', 'descripcion' => 'Desde la prehistoria hasta la era moderna', 'progreso' => 0, 'color' => '#AED581'],
    ['id' => 5, 'nombre' => 'Arte', 'profesor' => 'Jorge Ramírez', 'descripcion' => 'Crea, imagina y expresa con diversas técnicas artísticas', 'progreso' => 0, 'color' => '#AED581'],
    ['id' => 6, 'nombre' => 'Educacion por el Trabajo', 'profesor' => 'Jorge Ramírez', 'descripcion' => 'Desarrolla habilidades técnicas a través de manualidades escolares', 'progreso' => 0, 'color' => '#AED581'],
    ['id' => 7, 'nombre' => 'Educacion Religiosa', 'profesor' => 'Carlos Zapata', 'descripcion' => 'Fortalece valores cristianos para la vida en sociedad', 'progreso' => 0, 'color' => '#AED581'],
    ['id' => 8, 'nombre' => 'Computación', 'profesor' => 'luis Chavez', 'descripcion' => 'Desarrolla habilidades digitales para el entorno educativo actual', 'progreso' => 0, 'color' => '#AED581']
    
];
?>

<div class="header">
    <h1 class="page-title">Mis Cursos</h1>
</div>

<div class="courses-container">
    <div class="courses-header">
        <h2 class="courses-title">Todos tus cursos</h2>
        <!-- <input type="text" class="search-box" placeholder="Buscar cursos..."> -->
    </div>

    <?php if (count($cursos) > 0): ?>
        <div class="courses-grid">
            <?php foreach ($cursos as $curso): ?>
                <div class="course-card">
                    <div class="course-header">
                        <h3 class="course-title"><?= htmlspecialchars($curso['nombre']) ?></h3>
                        <p class="course-teacher">Profesor: <?= htmlspecialchars($curso['profesor']) ?></p>
                        <p class="course-description"><?= htmlspecialchars($curso['descripcion']) ?></p>

                        <div class="progress-container">
                            <div class="progress-label">
                                <span>Progreso</span>
                                <span><?= $curso['progreso'] ?>%</span>
                            </div>
                            <div class="progress-bar">
                                <div class="progress-fill" style="width: <?= $curso['progreso'] ?>%; background-color: <?= $curso['color'] ?>"></div>
                            </div>
                        </div>
                    </div>
                    <div class="course-actions">
                        <a href="detallecurso.php?id=<?= $curso['id'] ?>" class="btn btn-primary">
                            <i class="fas fa-book-open"></i> Entrar
                        </a>
                        <a href="#" class="btn btn-outline">
                            <i class="fas fa-info-circle"></i> Detalles
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="empty-state">
            <i class="fas fa-book-open"></i>
            <h3>No tienes cursos asignados</h3>
            <p>Actualmente no estás inscrito en ningún curso. Consulta con tu administrador.</p>
        </div>
    <?php endif; ?>
</div>
