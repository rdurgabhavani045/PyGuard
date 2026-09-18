document.addEventListener("DOMContentLoaded", function () {

    console.log("PyGuard JavaScript loaded successfully");

    // ===============================
    // GET HTML ELEMENTS
    // ===============================

    const codeInput = document.getElementById("codeInput");
    const analyzeBtn = document.getElementById("analyzeBtn");
    const clearBtn = document.getElementById("clearBtn");
    const fileInput = document.getElementById("fileInput");

    const resultsSection = document.getElementById("resultsSection");

    // Result counters
    const errorCount = document.getElementById("errorCount");
    const warningCount = document.getElementById("warningCount");
    const securityCount = document.getElementById("securityCount");
    const styleCount = document.getElementById("styleCount");

    // Score
    const score = document.getElementById("score");
    const qualityText = document.getElementById("qualityText");

    // Issues
    const totalIssues = document.getElementById("totalIssues");
    const issuesList = document.getElementById("issuesList");
    const suggestionsList = document.getElementById("suggestionsList");

    // File information
    const fileName = document.getElementById("fileName");
    const resultFile = document.getElementById("resultFile");

    // Editor information
    const lineNumbers = document.getElementById("lineNumbers");
    const lineCount = document.getElementById("lineCount");
    const charCount = document.getElementById("charCount");


    // ===============================
    // CHECK ELEMENTS
    // ===============================

    console.log("codeInput:", codeInput);
    console.log("analyzeBtn:", analyzeBtn);
    console.log("resultsSection:", resultsSection);
    console.log("errorCount:", errorCount);
    console.log("warningCount:", warningCount);
    console.log("securityCount:", securityCount);
    console.log("styleCount:", styleCount);


    // ===============================
    // UPDATE EDITOR INFORMATION
    // ===============================

    function updateEditorInfo() {

        if (!codeInput) return;

        const code = codeInput.value;

        const lines = code.length === 0
            ? 1
            : code.split("\n").length;

        if (lineCount) {
            lineCount.textContent = "Lines: " + lines;
        }

        if (charCount) {
            charCount.textContent = "Characters: " + code.length;
        }

        if (lineNumbers) {

            let numbers = "";

            for (let i = 1; i <= lines; i++) {
                numbers += i + "\n";
            }

            lineNumbers.textContent = numbers;
        }
    }


    // ===============================
    // CODE INPUT EVENT
    // ===============================

    if (codeInput) {

        codeInput.addEventListener("input", function () {
            updateEditorInfo();
        });


        // TAB SUPPORT
        codeInput.addEventListener("keydown", function (event) {

            if (event.key === "Tab") {

                event.preventDefault();

                const start = this.selectionStart;
                const end = this.selectionEnd;

                this.value =
                    this.value.substring(0, start) +
                    "    " +
                    this.value.substring(end);

                this.selectionStart =
                    this.selectionEnd = start + 4;

                updateEditorInfo();
            }
        });
    }


    // ===============================
    // UPLOAD PYTHON FILE
    // ===============================

    if (fileInput) {

        fileInput.addEventListener("change", function () {

            const file = this.files[0];

            if (!file) {
                return;
            }

            if (!file.name.toLowerCase().endsWith(".py")) {

                alert("Please select a Python (.py) file.");

                fileInput.value = "";

                return;
            }

            const reader = new FileReader();

            reader.onload = function (event) {

                codeInput.value = event.target.result;

                if (fileName) {
                    fileName.textContent = file.name;
                }

                updateEditorInfo();

                console.log("Python file loaded:", file.name);
            };

            reader.readAsText(file);
        });
    }


    // ===============================
    // CLEAR BUTTON
    // ===============================

    if (clearBtn) {

        clearBtn.addEventListener("click", function () {

            codeInput.value = "";

            if (fileName) {
                fileName.textContent = "Untitled.py";
            }

            if (fileInput) {
                fileInput.value = "";
            }

            if (resultsSection) {
                resultsSection.classList.add("hidden");
            }

            updateEditorInfo();

            console.log("Editor cleared");
        });
    }


    // ===============================
    // ANALYZE BUTTON
    // ===============================

    if (analyzeBtn) {

        analyzeBtn.addEventListener("click", function (event) {

            event.preventDefault();

            console.log("Analyze button clicked");

            const code = codeInput.value.trim();

            if (code === "") {

                alert("Please enter or upload Python code first.");

                return;
            }

            analyzePythonCode(code);
        });

    } else {

        console.error("Analyze button not found!");

    }


    // ===============================
    // PYTHON ANALYZER
    // ===============================

    function analyzePythonCode(code) {

        console.log("Starting Python analysis...");

        let errors = [];
        let warnings = [];
        let security = [];
        let styles = [];
        let suggestions = [];


        const lines = code.split("\n");


        // =================================
        // 1. BASIC SYNTAX CHECK
        // =================================

        lines.forEach(function (line, index) {

            const trimmed = line.trim();

            const lineNumber = index + 1;

            if (trimmed === "") {
                return;
            }


            // Missing colon
            if (
                /^(if|elif|else|for|while|def|class|try|except|finally|with)\b/.test(trimmed)
                && !trimmed.endsWith(":")
                && !trimmed.startsWith("#")
            ) {

                errors.push({
                    type: "Error",
                    line: lineNumber,
                    message: "Possible missing ':' at the end of this statement."
                });

            }


            // Unclosed brackets
            const openBrackets =
                (line.match(/[\(\[\{]/g) || []).length;

            const closeBrackets =
                (line.match(/[\)\]\}]/g) || []).length;

            if (openBrackets !== closeBrackets) {

                errors.push({
                    type: "Error",
                    line: lineNumber,
                    message: "Possible unmatched brackets."
                });

            }

        });


        // =================================
        // 2. SECURITY CHECK
        // =================================

        lines.forEach(function (line, index) {

            const lower = line.toLowerCase();

            const lineNumber = index + 1;


            // eval()
            if (/\beval\s*\(/.test(line)) {

                security.push({
                    type: "Security",
                    line: lineNumber,
                    message: "Avoid using eval(). It can execute untrusted code."
                });

            }


            // exec()
            if (/\bexec\s*\(/.test(line)) {

                security.push({
                    type: "Security",
                    line: lineNumber,
                    message: "Avoid using exec(). It can execute arbitrary code."
                });

            }


            // Password
            if (
                /password\s*=\s*["']/.test(lower)
                ||
                /passwd\s*=\s*["']/.test(lower)
            ) {

                security.push({
                    type: "Security",
                    line: lineNumber,
                    message: "Hardcoded password detected."
                });

            }


            // API key
            if (
                /api_key\s*=\s*["']/.test(lower)
                ||
                /apikey\s*=\s*["']/.test(lower)
            ) {

                security.push({
                    type: "Security",
                    line: lineNumber,
                    message: "Hardcoded API key detected."
                });

            }

        });


        // =================================
        // 3. CODE STYLE CHECK
        // =================================

        lines.forEach(function (line, index) {

            const lineNumber = index + 1;


            // Long lines
            if (line.length > 100) {

                styles.push({
                    type: "Style",
                    line: lineNumber,
                    message: "Line is longer than 100 characters."
                });

            }


            // Tabs
            if (line.includes("\t")) {

                styles.push({
                    type: "Style",
                    line: lineNumber,
                    message: "Use spaces instead of tabs for indentation."
                });

            }


            // Print
            if (/^\s*print\s*\(/.test(line)) {

                styles.push({
                    type: "Style",
                    line: lineNumber,
                    message: "Consider using logging instead of print() in larger applications."
                });

            }

        });


        // =================================
        // 4. CODE QUALITY
        // =================================

        const complexityCount =
            (code.match(/\b(if|elif|for|while)\b/g) || []).length;

        if (complexityCount > 5) {

            warnings.push({
                type: "Warning",
                line: 1,
                message: "High control-flow complexity detected."
            });

            suggestions.push(
                "Consider breaking complex logic into smaller functions."
            );
        }


        // Unused variable - simple detection
        lines.forEach(function (line, index) {

            const match = line.match(/^\s*(\w+)\s*=\s*(.+)$/);

            if (!match) {
                return;
            }

            const variable = match[1];
            const lineNumber = index + 1;

            const occurrences =
                code.match(new RegExp("\\b" + variable + "\\b", "g")) || [];

            if (occurrences.length === 1) {

                warnings.push({
                    type: "Warning",
                    line: lineNumber,
                    message: "Variable '" + variable + "' may be unused."
                });

            }

        });


        // =================================
        // 5. SUGGESTIONS
        // =================================

        suggestions.push(
            "Keep your functions small and readable."
        );

        suggestions.push(
            "Use meaningful variable and function names."
        );

        suggestions.push(
            "Avoid unnecessary imports."
        );

        if (security.length > 0) {

            suggestions.push(
                "Store passwords and API keys using environment variables."
            );

        }

        if (styles.length > 0) {

            suggestions.push(
                "Follow consistent Python formatting and indentation."
            );

        }


        // =================================
        // TOTAL COUNTS
        // =================================

        const errorTotal = errors.length;
        const warningTotal = warnings.length;
        const securityTotal = security.length;
        const styleTotal = styles.length;

        const total =
            errorTotal +
            warningTotal +
            securityTotal +
            styleTotal;


        // =================================
        // QUALITY SCORE
        // =================================

        let qualityScore = 100;

        qualityScore -= errorTotal * 20;
        qualityScore -= warningTotal * 5;
        qualityScore -= securityTotal * 15;
        qualityScore -= styleTotal * 2;

        if (qualityScore < 0) {
            qualityScore = 0;
        }

        if (qualityScore > 100) {
            qualityScore = 100;
        }


        let quality = "Excellent";

        if (qualityScore >= 90) {

            quality = "Excellent";

        } else if (qualityScore >= 75) {

            quality = "Good";

        } else if (qualityScore >= 50) {

            quality = "Average";

        } else {

            quality = "Needs Improvement";

        }


        // =================================
        // UPDATE COUNTERS
        // =================================

        console.log("Errors:", errorTotal);
        console.log("Warnings:", warningTotal);
        console.log("Security:", securityTotal);
        console.log("Style:", styleTotal);
        console.log("Total:", total);
        console.log("Score:", qualityScore);


        if (errorCount) {
            errorCount.textContent = errorTotal;
        }

        if (warningCount) {
            warningCount.textContent = warningTotal;
        }

        if (securityCount) {
            securityCount.textContent = securityTotal;
        }

        if (styleCount) {
            styleCount.textContent = styleTotal;
        }

        if (score) {
            score.textContent = qualityScore;
        }

        if (qualityText) {
            qualityText.textContent = quality;
        }

        if (totalIssues) {
            totalIssues.textContent = total + " Issues";
        }

        if (resultFile && fileName) {
            resultFile.textContent = fileName.textContent;
        }


        // =================================
        // DISPLAY ISSUES
        // =================================

        if (issuesList) {

            issuesList.innerHTML = "";

            const allIssues = [
                ...errors,
                ...warnings,
                ...security,
                ...styles
            ];


            if (allIssues.length === 0) {

                issuesList.innerHTML = `
                    <div class="issue-item">
                        <div class="issue-symbol">✓</div>
                        <div class="issue-body">
                            <strong>No issues found</strong>
                            <p>Your Python code passed all current checks.</p>
                        </div>
                    </div>
                `;

            } else {

                allIssues.forEach(function (issue) {

                    issuesList.innerHTML += `
                        <div class="issue-item">

                            <div class="issue-symbol">
                                ${getIssueIcon(issue.type)}
                            </div>

                            <div class="issue-body">

                                <strong>
                                    ${issue.type} - Line ${issue.line}
                                </strong>

                                <p>
                                    ${issue.message}
                                </p>

                            </div>

                        </div>
                    `;

                });

            }

        }


        // =================================
        // DISPLAY SUGGESTIONS
        // =================================

        if (suggestionsList) {

            suggestionsList.innerHTML = "";

            suggestions.forEach(function (suggestion) {

                suggestionsList.innerHTML += `
                    <div class="suggestion">

                        <span>✓</span>

                        <p>
                            ${suggestion}
                        </p>

                    </div>
                `;

            });

        }


        // =================================
        // SHOW RESULTS
        // =================================

        if (resultsSection) {

            resultsSection.classList.remove("hidden");

            resultsSection.scrollIntoView({
                behavior: "smooth",
                block: "start"
            });

        }

        console.log("Analysis completed successfully");
    }


    // ===============================
    // ISSUE ICON
    // ===============================

    function getIssueIcon(type) {

        if (type === "Error") {
            return "❌";
        }

        if (type === "Warning") {
            return "⚠️";
        }

        if (type === "Security") {
            return "🔐";
        }

        if (type === "Style") {
            return "🎨";
        }

        return "ℹ️";
    }


    // ===============================
    // INITIAL UPDATE
    // ===============================

    updateEditorInfo();

});