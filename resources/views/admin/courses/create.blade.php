<!-- Bulk Modules Upload -->
<div class="card mt-24">
    <div class="card-header">
        <h6 class="card-title mb-0">Bulk Modules Upload</h6>
    </div>
    <div class="card-body">
        <div class="alert alert-info">
            <iconify-icon icon="mdi:information" class="icon me-2"></iconify-icon>
            <strong>Format Instructions:</strong>
            <ul class="mb-0 mt-2">
                <li>Use the format: "Module X: Title" on a new line</li>
                <li>Follow with module description</li>
                <li>Add sections like "Objectives:", "Topics:", "Case Study:", "Exercise:"</li>
                <li>Separate modules with a blank line</li>
            </ul>
        </div>
        
        <div class="mb-3">
            <label class="form-label">Paste Module Content</label>
            <textarea name="bulk_modules" class="form-control" rows="15" 
                      placeholder="Example:
Module 1: Foundations of Governance, Risk and Compliance
This module provides the fundamental understanding of GRC concepts...

Objectives:
- Understand GRC concepts and history
- Explain the purpose of governance

Topics:
- Evolution of GRC
- Integrated GRC model
- Stakeholder theory

Case Study:
A multinational bank fails due to weak board oversight...

Exercise:
Map GRC responsibilities in your organisation.

Module 2: Corporate Governance & Ethics
..."></textarea>
        </div>
        
        <div class="mb-3">
            <label class="form-label">Or Upload Document</label>
            <input type="file" name="document" class="form-control" accept=".txt,.doc,.docx,.pdf">
            <small class="text-muted">Supported formats: TXT, DOC, DOCX, PDF (Max: 10MB)</small>
        </div>
        
        <div class="form-text">
            <strong>Quick Format Tips:</strong>
            <div class="d-flex flex-wrap gap-2 mt-2">
                <span class="badge bg-light text-dark">Module X: Title</span>
                <span class="badge bg-light text-dark">Objectives:</span>
                <span class="badge bg-light text-dark">Topics:</span>
                <span class="badge bg-light text-dark">Case Study:</span>
                <span class="badge bg-light text-dark">Exercise:</span>
            </div>
        </div>
    </div>
</div>

<!-- Course Materials Upload -->
<div class="card mt-24">
    <div class="card-header">
        <h6 class="card-title mb-0">Course Materials</h6>
    </div>
    <div class="card-body">
        <div class="mb-3">
            <label class="form-label">Upload Materials</label>
            <input type="file" name="materials[]" class="form-control" multiple 
                   accept=".pdf,.doc,.docx,.ppt,.pptx,.xls,.xlsx,.txt,.zip,.rar">
            <small class="text-muted">Supported formats: PDF, DOC, DOCX, PPT, XLS, TXT, ZIP, RAR (Max: 10MB each)</small>
        </div>
        
        <div class="row gy-3">
            <div class="col-md-6">
                <label class="form-label">Material Type</label>
                <select name="material_type" class="form-select">
                    <option value="manual">Course Manual</option>
                    <option value="presentation">Presentation</option>
                    <option value="worksheet">Worksheet</option>
                    <option value="template">Template</option>
                    <option value="reference">Reference Material</option>
                </select>
            </div>
            
            <div class="col-md-6">
                <div class="form-check mt-4 pt-2">
                    <input class="form-check-input" type="checkbox" name="is_downloadable" id="is_downloadable" value="1" checked>
                    <label class="form-check-label" for="is_downloadable">
                        Allow students to download
                    </label>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Detailed Course Information -->
<div class="