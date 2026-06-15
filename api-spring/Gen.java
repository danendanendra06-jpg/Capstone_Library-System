import java.io.File;
import java.io.FileWriter;
import java.io.IOException;

public class Gen {

    private static final String BASE_PATH = "src/main/java/com/capstone/";

    public static void main(String[] args) throws IOException {
        String[] entities = {"Category", "Book", "Review", "Cart", "CartItem", "Borrow", "BookReturn", "Fine", "Notification"};
        
        for (String entity : entities) {
            createRepository(entity);
            createDto(entity);
            createService(entity);
            createController(entity);
        }
        
        System.out.println("Generation complete.");
    }

    private static void createRepository(String entity) throws IOException {
        String content = "package com.capstone.repository;\n\n" +
                "import com.capstone.model." + entity + ";\n" +
                "import org.springframework.data.domain.Page;\n" +
                "import org.springframework.data.domain.Pageable;\n" +
                "import org.springframework.data.jpa.repository.JpaRepository;\n" +
                "import org.springframework.stereotype.Repository;\n\n" +
                "@Repository\n" +
                "public interface " + entity + "Repository extends JpaRepository<" + entity + ", Long> {\n" +
                "}\n";
        writeFile("repository/" + entity + "Repository.java", content);
    }

    private static void createDto(String entity) throws IOException {
        String content = "package com.capstone.dto;\n\n" +
                "public class " + entity + "DTO {\n" +
                "    private Long id;\n" +
                "    // Add specific fields for " + entity + " here\n" +
                "    public Long getId() { return id; }\n" +
                "    public void setId(Long id) { this.id = id; }\n" +
                "}\n";
        writeFile("dto/" + entity + "DTO.java", content);
    }

    private static void createService(String entity) throws IOException {
        String lEntity = entity.substring(0, 1).toLowerCase() + entity.substring(1);
        String content = "package com.capstone.service;\n\n" +
                "import com.capstone.model." + entity + ";\n" +
                "import com.capstone.repository." + entity + "Repository;\n" +
                "import com.capstone.exception.ResourceNotFoundException;\n" +
                "import org.springframework.beans.factory.annotation.Autowired;\n" +
                "import org.springframework.data.domain.Page;\n" +
                "import org.springframework.data.domain.Pageable;\n" +
                "import org.springframework.stereotype.Service;\n\n" +
                "import java.util.Optional;\n\n" +
                "@Service\n" +
                "public class " + entity + "Service {\n" +
                "    @Autowired\n" +
                "    private " + entity + "Repository repository;\n\n" +
                "    public Page<" + entity + "> getAll(Pageable pageable) {\n" +
                "        return repository.findAll(pageable);\n" +
                "    }\n\n" +
                "    public " + entity + " getById(Long id) {\n" +
                "        return repository.findById(id).orElseThrow(() -> new ResourceNotFoundException(\"" + entity + " not found\"));\n" +
                "    }\n\n" +
                "    public " + entity + " save(" + entity + " " + lEntity + ") {\n" +
                "        return repository.save(" + lEntity + ");\n" +
                "    }\n\n" +
                "    public void delete(Long id) {\n" +
                "        " + entity + " obj = getById(id);\n" +
                "        repository.delete(obj);\n" +
                "    }\n" +
                "}\n";
        writeFile("service/" + entity + "Service.java", content);
    }

    private static void createController(String entity) throws IOException {
        String lEntity = entity.substring(0, 1).toLowerCase() + entity.substring(1);
        String mapping = entity.replaceAll("([a-z])([A-Z]+)", "$1-$2").toLowerCase() + "s";
        
        String content = "package com.capstone.controller;\n\n" +
                "import com.capstone.model." + entity + ";\n" +
                "import com.capstone.service." + entity + "Service;\n" +
                "import org.springframework.beans.factory.annotation.Autowired;\n" +
                "import org.springframework.data.domain.Page;\n" +
                "import org.springframework.data.domain.Pageable;\n" +
                "import org.springframework.http.ResponseEntity;\n" +
                "import org.springframework.web.bind.annotation.*;\n" +
                "import org.springframework.security.access.prepost.PreAuthorize;\n\n" +
                "@RestController\n" +
                "@RequestMapping(\"/api/" + mapping + "\")\n" +
                "public class " + entity + "Controller {\n" +
                "    @Autowired\n" +
                "    private " + entity + "Service service;\n\n" +
                "    @GetMapping\n" +
                "    public ResponseEntity<Page<" + entity + ">> getAll(Pageable pageable) {\n" +
                "        return ResponseEntity.ok(service.getAll(pageable));\n" +
                "    }\n\n" +
                "    @GetMapping(\"/{id}\")\n" +
                "    public ResponseEntity<" + entity + "> getById(@PathVariable Long id) {\n" +
                "        return ResponseEntity.ok(service.getById(id));\n" +
                "    }\n\n" +
                "    @PostMapping\n" +
                "    @PreAuthorize(\"hasRole('ADMIN') or hasRole('LIBRARIAN')\")\n" +
                "    public ResponseEntity<" + entity + "> create(@RequestBody " + entity + " " + lEntity + ") {\n" +
                "        return ResponseEntity.ok(service.save(" + lEntity + "));\n" +
                "    }\n\n" +
                "    @PutMapping(\"/{id}\")\n" +
                "    @PreAuthorize(\"hasRole('ADMIN') or hasRole('LIBRARIAN')\")\n" +
                "    public ResponseEntity<" + entity + "> update(@PathVariable Long id, @RequestBody " + entity + " " + lEntity + ") {\n" +
                "        " + entity + " existing = service.getById(id);\n" +
                "        " + lEntity + ".setId(id);\n" +
                "        return ResponseEntity.ok(service.save(" + lEntity + "));\n" +
                "    }\n\n" +
                "    @DeleteMapping(\"/{id}\")\n" +
                "    @PreAuthorize(\"hasRole('ADMIN') or hasRole('LIBRARIAN')\")\n" +
                "    public ResponseEntity<Void> delete(@PathVariable Long id) {\n" +
                "        service.delete(id);\n" +
                "        return ResponseEntity.noContent().build();\n" +
                "    }\n" +
                "}\n";
        writeFile("controller/" + entity + "Controller.java", content);
    }

    private static void writeFile(String subPath, String content) throws IOException {
        File file = new File(BASE_PATH + subPath);
        file.getParentFile().mkdirs();
        try (FileWriter fw = new FileWriter(file)) {
            fw.write(content);
        }
    }
}
