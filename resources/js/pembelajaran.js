/**
 * Zarnizar Learning - Interactive Engine
 * Pembelajaran Informatika Kelas XII: Membaca dan Memahami Kode Python
 */

(function () {
    'use strict';

    const STORAGE_KEY = 'zl_pembelajaran_v1';

    // Safe LocalStorage Helper
    const Storage = {
        get() {
            try {
                const data = localStorage.getItem(STORAGE_KEY);
                return data ? JSON.parse(data) : this.defaultState();
            } catch (e) {
                console.warn('LocalStorage tidak tersedia atau diblokir:', e);
                return this.defaultState();
            }
        },

        save(state) {
            try {
                localStorage.setItem(STORAGE_KEY, JSON.stringify(state));
                return true;
            } catch (e) {
                console.warn('Gagal menyimpan ke LocalStorage:', e);
                return false;
            }
        },

        defaultState() {
            return {
                completed_meetings: [],
                meeting_quizzes: {},
                assessment: null,
            };
        },

        reset() {
            try {
                localStorage.removeItem(STORAGE_KEY);
                return true;
            } catch (e) {
                return false;
            }
        },

        markMeetingComplete(meetingId) {
            const state = this.get();
            const id = parseInt(meetingId, 10);
            if (!state.completed_meetings.includes(id)) {
                state.completed_meetings.push(id);
                this.save(state);
            }
        },

        isMeetingComplete(meetingId) {
            const state = this.get();
            return state.completed_meetings.includes(parseInt(meetingId, 10));
        },
    };

    // Expose Storage globally for debug or reset
    window.ZLStorage = Storage;

    // Run on DOM Ready
    document.addEventListener('DOMContentLoaded', () => {
        initResetModal();
        initHomePage();
        initTracer();
        initPredictExercise();
        initMeetingQuiz();
        initAssessment();
        initResultPage();
        initPlayground();
    });

    /**
     * =========================================================================
     * 1. RESET PROGRESS MODAL
     * =========================================================================
     */
    function initResetModal() {
        const resetBtns = document.querySelectorAll('.zl-trigger-reset');
        resetBtns.forEach((btn) => {
            btn.addEventListener('click', (e) => {
                e.preventDefault();
                if (confirm('Apakah Anda yakin ingin mereset seluruh progres pembelajaran dan hasil asesmen? Data di perangkat ini akan dikosongkan.')) {
                    Storage.reset();
                    alert('Progres belajar telah direset.');
                    window.location.reload();
                }
            });
        });
    }

    /**
     * =========================================================================
     * 2. HOME DASHBOARD PROGRESS
     * =========================================================================
     */
    function initHomePage() {
        const homeContainer = document.querySelector('.zl-home-dashboard');
        if (!homeContainer) return;

        const state = Storage.get();
        const completedCount = state.completed_meetings.length;
        const totalMeetings = 4;
        const percent = Math.round((completedCount / totalMeetings) * 100);

        // Update progress bar
        const progressBar = document.getElementById('zl-overall-progress');
        const progressText = document.getElementById('zl-progress-text');
        const completedCountEl = document.getElementById('zl-completed-count');

        if (progressBar) progressBar.style.width = percent + '%';
        if (progressText) progressText.textContent = percent + '% Selesai';
        if (completedCountEl) completedCountEl.textContent = `${completedCount} dari ${totalMeetings} Pertemuan Selesai`;

        // Update each meeting card badge
        for (let i = 1; i <= totalMeetings; i++) {
            const cardBadge = document.getElementById(`zl-card-status-${i}`);
            const cardBtn = document.getElementById(`zl-card-btn-${i}`);
            if (cardBadge) {
                if (state.completed_meetings.includes(i)) {
                    cardBadge.className = 'zl-badge zl-badge-success';
                    cardBadge.innerHTML = '<i class="bi bi-check-circle-fill"></i> Selesai';
                    if (cardBtn) cardBtn.innerHTML = 'Buka Lagi <i class="bi bi-arrow-right"></i>';
                } else {
                    cardBadge.className = 'zl-badge zl-badge-primary';
                    cardBadge.innerHTML = '<i class="bi bi-book"></i> Siap Dipelajari';
                }
            }
        }

        // Update assessment banner
        const assessBadge = document.getElementById('zl-home-assess-status');
        if (assessBadge) {
            if (state.assessment) {
                const isPassed = state.assessment.kktp_passed;
                assessBadge.className = `zl-badge ${isPassed ? 'zl-badge-success' : 'zl-badge-warning'}`;
                assessBadge.innerHTML = `<i class="bi bi-award-fill"></i> Skor Terakhir: ${state.assessment.score}/100 (${isPassed ? 'Tuntas' : 'Perlu Bimbingan'})`;
            } else {
                assessBadge.className = 'zl-badge zl-badge-primary';
                assessBadge.innerHTML = '<i class="bi bi-clock"></i> Belum Dikerjakan';
            }
        }
    }

    /**
     * =========================================================================
     * 3. INTERACTIVE CODE TRACER (CRITICAL FEATURE)
     * =========================================================================
     */
    function initTracer() {
        const tracerContainers = document.querySelectorAll('.zl-tracer-widget');
        tracerContainers.forEach((container) => {
            const rawSteps = container.getAttribute('data-trace-steps');
            if (!rawSteps) return;

            let steps = [];
            try {
                steps = JSON.parse(rawSteps);
            } catch (err) {
                console.error('Error parsing trace steps:', err);
                return;
            }

            if (!steps || steps.length === 0) return;

            let currentStepIdx = 0;

            const codeContainer = container.querySelector('.zl-tracer-code-viewer');
            const stepCounter = container.querySelector('.zl-tracer-step-counter');
            const noteEl = container.querySelector('.zl-tracer-note');
            const prevBtn = container.querySelector('.zl-tracer-prev-btn');
            const nextBtn = container.querySelector('.zl-tracer-next-btn');
            const resetBtn = container.querySelector('.zl-tracer-reset-btn');
            const memoryTableBody = container.querySelector('.zl-tracer-memory-body');
            const terminalEl = container.querySelector('.zl-tracer-terminal');

            function renderStep(index) {
                const step = steps[index];
                if (!step) return;

                // 1. Update Step Counter
                if (stepCounter) {
                    stepCounter.textContent = `Langkah ${index + 1} dari ${steps.length}`;
                }

                // 2. Highlight Line in Code Viewer
                if (codeContainer) {
                    const lines = codeContainer.querySelectorAll('.zl-code-line');
                    lines.forEach((line) => {
                        const lineNum = parseInt(line.getAttribute('data-line'), 10);
                        if (lineNum === step.line) {
                            line.classList.add('zl-active-line');
                            // Ensure visible in view
                            line.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                        } else {
                            line.classList.remove('zl-active-line');
                        }
                    });
                }

                // 3. Update Memory Watch Table
                if (memoryTableBody) {
                    memoryTableBody.innerHTML = '';
                    const vars = step.vars || {};
                    const varKeys = Object.keys(vars);

                    if (varKeys.length === 0) {
                        memoryTableBody.innerHTML = `<tr><td colspan="3" class="text-muted text-center py-2">Belum ada variabel yang terbentuk</td></tr>`;
                    } else {
                        varKeys.forEach((key) => {
                            const val = vars[key];
                            const isChanged = step.changed_var === key;
                            const tr = document.createElement('tr');
                            if (isChanged) tr.className = 'zl-var-changed';

                            tr.innerHTML = `
                                <td class="fw-bold">${escapeHtml(key)}</td>
                                <td><code>${escapeHtml(String(val))}</code></td>
                                <td class="text-muted small">${isChanged ? '<span class="text-primary fw-bold">✓ Nilai Diperbarui</span>' : 'Tetap'}</td>
                            `;
                            memoryTableBody.appendChild(tr);
                        });
                    }
                }

                // 4. Update Terminal Output Screen
                if (terminalEl) {
                    terminalEl.innerHTML = '';
                    // Collect all outputs up to this step
                    const outputsSoFar = [];
                    for (let i = 0; i <= index; i++) {
                        if (steps[i].output !== null && steps[i].output !== undefined && steps[i].output !== '') {
                            outputsSoFar.push(steps[i].output);
                        }
                    }

                    if (outputsSoFar.length === 0) {
                        terminalEl.innerHTML = '<span class="text-secondary small italic">// Terminal belum menampilkan output...</span>';
                    } else {
                        outputsSoFar.forEach((out) => {
                            const div = document.createElement('div');
                            div.className = 'zl-term-line';
                            div.textContent = `> ${out}`;
                            terminalEl.appendChild(div);
                        });
                        terminalEl.scrollTop = terminalEl.scrollHeight;
                    }
                }

                // 5. Update Explanation Note
                if (noteEl) {
                    noteEl.innerHTML = `
                        <div class="d-flex align-items-start gap-2">
                            <span class="badge bg-primary text-white">Baris ${step.line}</span>
                            <div>${escapeHtml(step.note)}</div>
                        </div>
                    `;
                }

                // 6. Button states
                if (prevBtn) prevBtn.disabled = index === 0;
                if (nextBtn) {
                    if (index === steps.length - 1) {
                        nextBtn.disabled = true;
                        nextBtn.innerHTML = 'Selesai <i class="bi bi-check-circle"></i>';
                    } else {
                        nextBtn.disabled = false;
                        nextBtn.innerHTML = 'Langkah Berikutnya <i class="bi bi-chevron-right"></i>';
                    }
                }
            }

            // Event Listeners
            if (nextBtn) {
                nextBtn.addEventListener('click', () => {
                    if (currentStepIdx < steps.length - 1) {
                        currentStepIdx++;
                        renderStep(currentStepIdx);
                    }
                });
            }

            if (prevBtn) {
                prevBtn.addEventListener('click', () => {
                    if (currentStepIdx > 0) {
                        currentStepIdx--;
                        renderStep(currentStepIdx);
                    }
                });
            }

            if (resetBtn) {
                resetBtn.addEventListener('click', () => {
                    currentStepIdx = 0;
                    renderStep(0);
                });
            }

            // Initial render
            renderStep(0);
        });
    }

    /**
     * =========================================================================
     * 4. LATIHAN PREDIKSI OUTPUT
     * =========================================================================
     */
    function initPredictExercise() {
        const containers = document.querySelectorAll('.zl-predict-container');
        containers.forEach((container) => {
            const checkBtn = container.querySelector('.zl-predict-check-btn');
            const inputField = container.querySelector('.zl-predict-input');
            const resultBox = container.querySelector('.zl-predict-result');
            const expectedOutput = (container.getAttribute('data-expected') || '').trim();
            const explanation = container.getAttribute('data-explanation') || '';

            if (checkBtn && inputField && resultBox) {
                checkBtn.addEventListener('click', () => {
                    const userVal = inputField.value.trim();
                    if (!userVal) {
                        alert('Silakan tulis tebakan output Anda terlebih dahulu!');
                        return;
                    }

                    // Flexible match (ignoring extra quotation or trailing spaces)
                    const normalizedUser = userVal.replace(/^["']|["']$/g, '').trim();
                    const normalizedExpected = expectedOutput.replace(/^["']|["']$/g, '').trim();
                    const isCorrect = normalizedUser.toLowerCase() === normalizedExpected.toLowerCase();

                    resultBox.style.display = 'block';
                    if (isCorrect) {
                        resultBox.className = 'zl-explanation-box zl-box-correct mt-3';
                        resultBox.innerHTML = `
                            <div class="fw-bold text-success mb-1"><i class="bi bi-check-circle-fill"></i> Prediksi Tepat Sekali!</div>
                            <p class="mb-1">Output program memang: <code>${escapeHtml(expectedOutput)}</code></p>
                            <p class="text-muted small mb-0">${escapeHtml(explanation)}</p>
                        `;
                    } else {
                        resultBox.className = 'zl-explanation-box zl-box-incorrect mt-3';
                        resultBox.innerHTML = `
                            <div class="fw-bold text-danger mb-1"><i class="bi bi-x-circle-fill"></i> Prediksi Belum Tepat</div>
                            <p class="mb-1">Jawaban Anda: <code>${escapeHtml(userVal)}</code></p>
                            <p class="mb-1">Output yang benar adalah: <code>${escapeHtml(expectedOutput)}</code></p>
                            <p class="text-muted small mb-0">${escapeHtml(explanation)}</p>
                        `;
                    }
                });
            }
        });
    }

    /**
     * =========================================================================
     * 5. KUIS PERTEMUAN (4 SOAL INTERAKTIF)
     * =========================================================================
     */
    function initMeetingQuiz() {
        const quizContainer = document.querySelector('.zl-meeting-quiz-container');
        if (!quizContainer) return;

        const meetingId = parseInt(quizContainer.getAttribute('data-meeting-id'), 10);
        const checkBtn = quizContainer.querySelector('.zl-quiz-submit-btn');
        const completeMeetingBtn = document.querySelector('.zl-btn-complete-meeting');
        const quizCards = quizContainer.querySelectorAll('.zl-quiz-card');

        // Style radio selection on change
        quizContainer.addEventListener('change', (e) => {
            if (e.target.matches('input[type="radio"]')) {
                const name = e.target.name;
                const radios = quizContainer.querySelectorAll(`input[name="${name}"]`);
                radios.forEach((r) => {
                    const label = r.closest('.zl-option-label');
                    if (label) {
                        if (r.checked) {
                            label.classList.add('zl-selected');
                        } else {
                            label.classList.remove('zl-selected');
                        }
                    }
                });
            }
        });

        if (checkBtn) {
            checkBtn.addEventListener('click', () => {
                let allAnswered = true;
                let correctCount = 0;
                const totalQuestions = quizCards.length;

                quizCards.forEach((card, idx) => {
                    const selected = card.querySelector('input[type="radio"]:checked');
                    if (!selected) {
                        allAnswered = false;
                    }
                });

                if (!allAnswered) {
                    alert('Harap jawab semua soal kuis terlebih dahulu sebelum memeriksa jawaban.');
                    return;
                }

                // Evaluate answers
                quizCards.forEach((card, idx) => {
                    const correctAnswer = card.getAttribute('data-correct-answer');
                    const selected = card.querySelector('input[type="radio"]:checked');
                    const selectedVal = selected ? selected.value : null;
                    const explanation = card.getAttribute('data-explanation') || '';
                    const explanationBox = card.querySelector('.zl-quiz-explanation');

                    const labels = card.querySelectorAll('.zl-option-label');
                    labels.forEach((label) => {
                        const input = label.querySelector('input[type="radio"]');
                        label.classList.remove('zl-correct', 'zl-incorrect');
                        if (input.value === correctAnswer) {
                            label.classList.add('zl-correct');
                        } else if (input.checked && input.value !== correctAnswer) {
                            label.classList.add('zl-incorrect');
                        }
                    });

                    if (selectedVal === correctAnswer) {
                        correctCount++;
                    }

                    if (explanationBox) {
                        explanationBox.style.display = 'block';
                        const isRight = selectedVal === correctAnswer;
                        explanationBox.className = `zl-explanation-box ${isRight ? 'zl-box-correct' : 'zl-box-incorrect'} mt-3`;
                        explanationBox.innerHTML = `
                            <div class="fw-bold ${isRight ? 'text-success' : 'text-danger'} mb-1">
                                <i class="bi bi-${isRight ? 'check-circle-fill' : 'x-circle-fill'}"></i> 
                                ${isRight ? 'Jawaban Benar!' : 'Jawaban Salah! Kunci Jawaban: ' + correctAnswer}
                            </div>
                            <div class="small">${escapeHtml(explanation)}</div>
                        `;
                    }
                });

                // Save status & Mark meeting as completed
                Storage.markMeetingComplete(meetingId);

                // Show completion badge
                const resultSummary = quizContainer.querySelector('.zl-quiz-result-summary');
                if (resultSummary) {
                    resultSummary.style.display = 'block';
                    resultSummary.innerHTML = `
                        <div class="alert alert-info border-2 border-dark shadow-sm mt-4">
                            <h5 class="fw-bold mb-2"><i class="bi bi-award"></i> Hasil Kuis Pertemuan ${meetingId}: ${correctCount} dari ${totalQuestions} Benar</h5>
                            <p class="mb-0">Progres pertemuan ini telah otomatis <strong>ditandai selesai</strong> dan tersimpan di perangkat Anda!</p>
                        </div>
                    `;
                }

                if (completeMeetingBtn) {
                    completeMeetingBtn.classList.remove('zl-btn-outline');
                    completeMeetingBtn.classList.add('zl-btn-success');
                    completeMeetingBtn.innerHTML = '<i class="bi bi-check-circle-fill"></i> Pertemuan Selesai!';
                }
            });
        }

        // Manual complete button
        if (completeMeetingBtn) {
            if (Storage.isMeetingComplete(meetingId)) {
                completeMeetingBtn.classList.remove('zl-btn-outline');
                completeMeetingBtn.classList.add('zl-btn-success');
                completeMeetingBtn.innerHTML = '<i class="bi bi-check-circle-fill"></i> Pertemuan Selesai!';
            }

            completeMeetingBtn.addEventListener('click', () => {
                Storage.markMeetingComplete(meetingId);
                completeMeetingBtn.classList.remove('zl-btn-outline');
                completeMeetingBtn.classList.add('zl-btn-success');
                completeMeetingBtn.innerHTML = '<i class="bi bi-check-circle-fill"></i> Pertemuan Selesai!';
                alert(`Pertemuan ${meetingId} berhasil ditandai selesai!`);
            });
        }
    }

    /**
     * =========================================================================
     * 6. ASESMEN AKHIR (10 SOAL @ 10 POIN = 100 POIN)
     * =========================================================================
     */
    function initAssessment() {
        const assessForm = document.getElementById('zl-assessment-form');
        if (!assessForm) return;

        const totalQuestions = 10;
        const submitBtn = document.getElementById('zl-submit-assessment-btn');
        const progressCountEl = document.getElementById('zl-answered-count');
        const studentNameInput = document.getElementById('zl-student-name');
        const studentClassInput = document.getElementById('zl-student-class');

        // Palette buttons
        const paletteBtns = document.querySelectorAll('.zl-palette-btn');

        function updateProgress() {
            let answered = 0;
            for (let i = 1; i <= totalQuestions; i++) {
                const checked = assessForm.querySelector(`input[name="q${i}"]:checked`);
                const paletteBtn = document.querySelector(`.zl-palette-btn[data-q="${i}"]`);
                if (checked) {
                    answered++;
                    if (paletteBtn) paletteBtn.classList.add('zl-palette-answered');
                } else {
                    if (paletteBtn) paletteBtn.classList.remove('zl-palette-answered');
                }
            }

            if (progressCountEl) {
                progressCountEl.textContent = `${answered} / ${totalQuestions} Terjawab`;
            }
        }

        // Palette scroll jump
        paletteBtns.forEach((btn) => {
            btn.addEventListener('click', () => {
                const qNum = btn.getAttribute('data-q');
                const targetCard = document.getElementById(`soal-${qNum}`);
                if (targetCard) {
                    targetCard.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    targetCard.classList.add('border-primary');
                    setTimeout(() => targetCard.classList.remove('border-primary'), 1500);
                }
            });
        });

        // Listen for answer changes
        assessForm.addEventListener('change', (e) => {
            if (e.target.matches('input[type="radio"]')) {
                const name = e.target.name;
                const radios = assessForm.querySelectorAll(`input[name="${name}"]`);
                radios.forEach((r) => {
                    const label = r.closest('.zl-option-label');
                    if (label) {
                        if (r.checked) {
                            label.classList.add('zl-selected');
                        } else {
                            label.classList.remove('zl-selected');
                        }
                    }
                });
                updateProgress();
            }
        });

        // Submit assessment
        if (submitBtn) {
            submitBtn.addEventListener('click', (e) => {
                e.preventDefault();

                // Validate completeness
                const unanswered = [];
                const userAnswers = {};

                for (let i = 1; i <= totalQuestions; i++) {
                    const checked = assessForm.querySelector(`input[name="q${i}"]:checked`);
                    if (!checked) {
                        unanswered.push(i);
                    } else {
                        userAnswers[i] = checked.value;
                    }
                }

                if (unanswered.length > 0) {
                    alert(`Perhatian! Anda belum menjawab ${unanswered.length} soal: Soal Nomor [${unanswered.join(', ')}].\nSemua 10 soal wajib dijawab sebelum mengirimkan asesmen.`);
                    // Jump to first unanswered
                    const firstUnanswered = document.getElementById(`soal-${unanswered[0]}`);
                    if (firstUnanswered) {
                        firstUnanswered.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    }
                    return;
                }

                if (!confirm('Apakah Anda yakin ingin mengirimkan jawaban asesmen ini? Jawaban akan diperiksa secara otomatis.')) {
                    return;
                }

                // Collect answer keys from data-attribute
                const answerKeys = {};
                for (let i = 1; i <= totalQuestions; i++) {
                    const card = document.getElementById(`soal-${i}`);
                    if (card) {
                        answerKeys[i] = card.getAttribute('data-kunci');
                    }
                }

                // Compute score
                let correctCount = 0;
                for (let i = 1; i <= totalQuestions; i++) {
                    if (userAnswers[i] === answerKeys[i]) {
                        correctCount++;
                    }
                }

                const score = correctCount * 10;
                const kktpThreshold = 75;
                const isPassed = score >= kktpThreshold;

                const studentName = (studentNameInput && studentNameInput.value.trim()) ? studentNameInput.value.trim() : 'Siswa Informatika XII';
                const studentClass = (studentClassInput && studentClassInput.value.trim()) ? studentClassInput.value.trim() : 'Kelas XII';

                const assessmentResult = {
                    student_name: studentName,
                    student_class: studentClass,
                    answers: userAnswers,
                    score: score,
                    correct_count: correctCount,
                    wrong_count: totalQuestions - correctCount,
                    total_questions: totalQuestions,
                    kktp_threshold: kktpThreshold,
                    kktp_passed: isPassed,
                    completed_at: new Date().toLocaleString('id-ID', {
                        day: 'numeric',
                        month: 'long',
                        year: 'numeric',
                        hour: '2-digit',
                        minute: '2-digit',
                    }),
                };

                const state = Storage.get();
                state.assessment = assessmentResult;
                Storage.save(state);

                // Redirect to Hasil page
                window.location.href = '/pembelajaran/hasil';
            });
        }
    }

    /**
     * =========================================================================
     * 7. HASIL ASESMEN & CETAK LAPORAN
     * =========================================================================
     */
    function initResultPage() {
        const resultContainer = document.querySelector('.zl-result-page');
        if (!resultContainer) return;

        const state = Storage.get();
        const res = state.assessment;

        const emptyNotice = document.getElementById('zl-result-empty');
        const contentSection = document.getElementById('zl-result-content');

        if (!res) {
            if (emptyNotice) emptyNotice.style.display = 'block';
            if (contentSection) contentSection.style.display = 'none';
            return;
        }

        if (emptyNotice) emptyNotice.style.display = 'none';
        if (contentSection) contentSection.style.display = 'block';

        // Populate fields
        setText('zl-res-name', res.student_name);
        setText('zl-res-class', res.student_class);
        setText('zl-res-date', res.completed_at);
        setText('zl-res-score', res.score);
        setText('zl-res-correct', `${res.correct_count} Benar`);
        setText('zl-res-wrong', `${res.wrong_count} Salah`);

        // Status KKTP
        const statusEl = document.getElementById('zl-res-kktp-badge');
        const motivationEl = document.getElementById('zl-res-motivation');

        if (statusEl) {
            if (res.kktp_passed) {
                statusEl.className = 'zl-badge zl-badge-success fs-6 py-2 px-3';
                statusEl.innerHTML = '<i class="bi bi-patch-check-fill"></i> TUNTAS (Mencapai KKTP 75)';
            } else {
                statusEl.className = 'zl-badge zl-badge-warning fs-6 py-2 px-3';
                statusEl.innerHTML = '<i class="bi bi-exclamation-triangle-fill"></i> PERLU PENGUATAN (< 75)';
            }
        }

        if (motivationEl) {
            if (res.score >= 90) {
                motivationEl.innerHTML = `<strong>Luar Biasa!</strong> Kemampuan Anda membaca dan menelusuri logika Python berada pada level mahir. Pertahankan ketelitian Anda!`;
            } else if (res.kktp_passed) {
                motivationEl.innerHTML = `<strong>Selamat!</strong> Anda telah mencapai kriteria ketuntasan minimal. Anda sudah mampu memahami alur sekuensial, percabangan, dan perulangan dengan baik.`;
            } else {
                motivationEl.innerHTML = `<strong>Tetap Semangat!</strong> Anda masih perlu memperdalam penelusuran variabel pada materi percabangan dan perulangan. Buka kembali materi Pertemuan 3 & 4 lalu coba ulangi asesmen.`;
            }
        }

        // Render Question Breakdown
        const breakdownCards = document.querySelectorAll('.zl-review-card');
        breakdownCards.forEach((card) => {
            const qNum = parseInt(card.getAttribute('data-q'), 10);
            const correctKey = card.getAttribute('data-kunci');
            const studentAns = res.answers ? res.answers[qNum] : null;

            const studentAnsBadge = card.querySelector('.zl-review-student-ans');
            const correctAnsBadge = card.querySelector('.zl-review-correct-ans');
            const reviewStatus = card.querySelector('.zl-review-status');

            if (studentAnsBadge) studentAnsBadge.textContent = studentAns || '-';
            if (correctAnsBadge) correctAnsBadge.textContent = correctKey;

            if (reviewStatus) {
                if (studentAns === correctKey) {
                    reviewStatus.className = 'zl-badge zl-badge-success';
                    reviewStatus.innerHTML = '<i class="bi bi-check-circle-fill"></i> Benar (+10 Poin)';
                    card.classList.add('border-success');
                } else {
                    reviewStatus.className = 'zl-badge zl-badge-danger';
                    reviewStatus.innerHTML = '<i class="bi bi-x-circle-fill"></i> Salah (0 Poin)';
                    card.classList.add('border-danger');
                }
            }
        });

        // Print button
        const printBtn = document.getElementById('zl-print-btn');
        if (printBtn) {
            printBtn.addEventListener('click', () => {
                window.print();
            });
        }
    }

    /**
     * =========================================================================
     * 8. IN-BROWSER PYTHON PLAYGROUND (LATIHAN TULIS KODE)
     * =========================================================================
     */
    function initPlayground() {
        const editor = document.getElementById('zl-playground-editor');
        const runBtn = document.getElementById('zl-run-code-btn');
        const terminal = document.getElementById('zl-playground-terminal');
        const statusEl = document.getElementById('zl-run-status');
        const clearBtn = document.getElementById('zl-clear-terminal-btn');
        const resetBtn = document.querySelector('.zl-reset-code-btn');
        const snippetBtns = document.querySelectorAll('.zl-snippet-btn');

        if (!editor || !runBtn || !terminal) return;

        // 1. Tab Key & Shortcut Support in Editor Textarea
        editor.addEventListener('keydown', (e) => {
            if (e.key === 'Tab') {
                e.preventDefault();
                const start = editor.selectionStart;
                const end = editor.selectionEnd;
                // Insert 4 spaces (standard Python indentation)
                editor.value = editor.value.substring(0, start) + '    ' + editor.value.substring(end);
                editor.selectionStart = editor.selectionEnd = start + 4;
            } else if (e.key === 'Enter' && (e.ctrlKey || e.metaKey)) {
                e.preventDefault();
                runBtn.click();
            }
        });

        // 2. Reset Code to Default
        if (resetBtn) {
            const defaultCode = editor.getAttribute('data-default-code') || '';
            resetBtn.addEventListener('click', () => {
                if (confirm('Kembalikan kode editor ke bentuk latihan awal?')) {
                    editor.value = defaultCode;
                    terminal.innerHTML = '<div class="text-secondary small italic">// Kode dikembalikan ke bentuk awal. Klik "Jalankan Kode ▶" untuk menjalankan.</div>';
                    if (statusEl) statusEl.textContent = 'Siap dijalankan';
                }
            });
        }

        // 3. Clear Terminal Button
        if (clearBtn) {
            clearBtn.addEventListener('click', () => {
                terminal.innerHTML = '<span class="text-secondary small italic">// Terminal bersih.</span>';
                if (statusEl) statusEl.textContent = 'Siap dijalankan';
            });
        }

        // 4. Quick Snippet Buttons
        snippetBtns.forEach((btn) => {
            btn.addEventListener('click', () => {
                const snippet = btn.getAttribute('data-snippet');
                if (!snippet) return;
                const start = editor.selectionStart;
                const end = editor.selectionEnd;
                editor.value = editor.value.substring(0, start) + snippet + editor.value.substring(end);
                editor.focus();
                editor.selectionStart = editor.selectionEnd = start + snippet.length;
            });
        });

        // 5. Execute Code using Skulpt (In-Browser Python)
        runBtn.addEventListener('click', () => {
            const code = editor.value;
            terminal.innerHTML = '';
            if (statusEl) statusEl.innerHTML = '<span class="text-warning"><i class="bi bi-hourglass-split"></i> Mengeksekusi kode...</span>';

            if (typeof Sk === 'undefined') {
                terminal.innerHTML = `
                    <div class="text-danger fw-bold mb-2"><i class="bi bi-wifi-off"></i> Interpreter Python (Skulpt) sedang memuat atau belum tersedia.</div>
                    <div class="text-secondary small">Pastikan perangkat Anda terhubung ke internet untuk memuat library awal (skulpt.min.js), lalu muat ulang halaman ini.</div>
                `;
                if (statusEl) statusEl.innerHTML = '<span class="text-danger"><i class="bi bi-x-circle"></i> Gagal Memuat Interpreter</span>';
                return;
            }

            // Ensure Python 3 futures object is valid (in case Sk.python3 was touched)
            const python3Features = (typeof Sk.python3 === 'object' && Sk.python3 !== null) ? Sk.python3 : {
                print_function: true,
                division: true,
                absolute_import: null,
                unicode_literals: true,
                python3: true
            };

            // Configure Skulpt Output, Builtins, and Python 3 Features
            Sk.configure({
                output: function (text) {
                    terminal.innerHTML += escapeHtml(text);
                },
                read: function (x) {
                    if (Sk.builtinFiles === undefined || Sk.builtinFiles['files'][x] === undefined) {
                        throw "File not found: '" + x + "'";
                    }
                    return Sk.builtinFiles['files'][x];
                },
                __future__: python3Features,
                execLimit: 5000, // Safe 5-second execution limit to avoid browser freezing from infinite loops
            });

            const promise = Sk.misceval.asyncToPromise(function () {
                return Sk.importMainWithBody('<stdin>', false, code, true);
            });

            promise.then(
                function () {
                    if (statusEl) statusEl.innerHTML = '<span class="text-success fw-bold"><i class="bi bi-check-circle-fill"></i> Selesai (Sukses)</span>';
                    // If no output was produced
                    if (terminal.innerHTML.trim() === '') {
                        terminal.innerHTML = '<span class="text-secondary small italic">// Program selesai dieksekusi tanpa pesan print().</span>';
                    }
                },
                function (err) {
                    if (statusEl) statusEl.innerHTML = '<span class="text-danger fw-bold"><i class="bi bi-x-circle-fill"></i> Error pada Program</span>';
                    terminal.innerHTML += `
                        <div class="text-danger mt-2 font-mono p-2 rounded" style="background: rgba(239, 68, 68, 0.15); border-left: 3px solid #ef4444;">
                            <div class="fw-bold"><i class="bi bi-exclamation-triangle-fill"></i> Pesan Error Python:</div>
                            <div class="small">${escapeHtml(err.toString())}</div>
                        </div>
                    `;
                }
            );
        });
    }

    function setText(id, text) {
        const el = document.getElementById(id);
        if (el) el.textContent = text;
    }

    function escapeHtml(str) {
        if (!str) return '';
        return String(str)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }
})();

